<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
$genderOpts = ['Male', 'Female', 'Other'];
$errors = [];
if (array_key_exists('submit', $_POST)) {
    if (!csrf_check('csrf', $_POST)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    
    // Handle file upload if a file was provided
    $avatarPath = null;
    if (isset($_FILES['avatar_upload']) && $_FILES['avatar_upload']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['avatar_upload'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        
        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            $errors[] = 'Failed to initialize file type detection.';
        } else {
            $mimeType = finfo_file($finfo, $uploadedFile['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                $errors[] = 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.';
            } elseif ($uploadedFile['size'] > $maxFileSize) {
                $errors[] = 'File size exceeds 2MB limit.';
            } else {
                // Map MIME types to safe extensions
                $mimeToExt = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp'
                ];
                $extension = $mimeToExt[$mimeType];
                
                // Generate unique filename based on user ID
                $filename = 'avatar_' . $user_class->id . '_' . time() . '.' . $extension;
                $uploadDir = __DIR__ . '/../images/avatars/';
                
                // Ensure upload directory exists and is writable
                if (!is_dir($uploadDir)) {
                    $errors[] = 'Upload directory does not exist.';
                } elseif (!is_writable($uploadDir)) {
                    $errors[] = 'Upload directory is not writable.';
                } else {
                    $uploadPath = $uploadDir . $filename;
                    
                    // Delete old avatar if it exists in avatars directory
                    if (!empty($user_class->avatar) && strpos($user_class->avatar, 'images/avatars/') === 0) {
                        $oldAvatarPath = __DIR__ . '/../' . $user_class->avatar;
                        if (file_exists($oldAvatarPath) && is_file($oldAvatarPath)) {
                            if (!unlink($oldAvatarPath)) {
                                log_warning('Failed to delete old avatar file', ['path' => $oldAvatarPath, 'user_id' => $user_class->id]);
                            }
                        }
                    }
                    
                    // Move uploaded file
                    if (move_uploaded_file($uploadedFile['tmp_name'], $uploadPath)) {
                        $avatarPath = 'images/avatars/' . $filename;
                    } else {
                        $errors[] = 'Failed to upload file. Please try again.';
                        log_warning('File upload failed', ['user_id' => $user_class->id, 'tmp_name' => $uploadedFile['tmp_name'], 'destination' => $uploadPath]);
                    }
                }
            }
        }
    } elseif (isset($_FILES['avatar_upload']) && $_FILES['avatar_upload']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Handle upload errors
        $errors[] = 'File upload error occurred. Please try again.';
    }
    
    // If no file was uploaded, check for URL input
    if ($avatarPath === null) {
        $_POST['avatar'] = array_key_exists('avatar', $_POST) && is_string($_POST['avatar']) ? $_POST['avatar'] : null;
        if (!empty($_POST['avatar']) && !isImage($_POST['avatar'])) {
            $errors[] = 'The avatar you selected hasn\'t validated as an image!';
        }
        $avatarPath = $_POST['avatar'];
    }
    
    $_POST['quote'] = array_key_exists('quote', $_POST) && is_string($_POST['quote']) ? strip_tags(trim($_POST['quote'])) : null;
    $_POST['gender'] = array_key_exists('gender', $_POST) && in_array($_POST['gender'], $genderOpts) ? $_POST['gender'] : null;
    if (!count($errors)) {
        $db->query('UPDATE users SET avatar = ?, quote = ?, gender = ? WHERE id = ?');
        $db->execute([$avatarPath, $_POST['quote'], $_POST['gender'], $user_class->id]);
        $user_class->avatar = (string)$avatarPath;
        $user_class->gender = (string)$_POST['gender'];
        $user_class->quote = (string)$_POST['quote'];
        echo Message('Your preferences have been saved.');
    }
}
?><tr>
    <th class="content-head">Account Preferences</th>
</tr><?php
if (count($errors)) {
    display_errors($errors);
}
?><tr>
    <td class="content">
        <form action="preferences.php" method="post" enctype="multipart/form-data" class="pure-form pure-form-aligned">
            <?php echo csrf_create(); ?>
            <fieldset>
                <?php if (!empty($user_class->avatar)) { ?>
                <div class="pure-control-group">
                    <label>Current Avatar</label>
                    <img src="<?php echo format($user_class->avatar); ?>" alt="Current Avatar" style="max-width: 100px; max-height: 100px; border: 1px solid #ccc;" />
                </div>
                <?php } ?>
                <div class="pure-control-group">
                    <label for="avatar_upload">Upload Avatar</label>
                    <input type="file" name="avatar_upload" id="avatar_upload" accept="image/jpeg,image/png,image/gif,image/webp" />
                    <span class="pure-form-message-inline">Max 2MB. Formats: JPEG, PNG, GIF, WebP</span>
                </div>
                <div class="pure-control-group">
                    <label for="avatar">Or Avatar URL</label>
                    <input type="text" name="avatar" id="avatar" value="<?php echo format($user_class->avatar); ?>" />
                </div>
                <div class="pure-control-group">
                    <label for="quote">Quote</label>
                    <input type="text" name="quote" id="quote" value="<?php echo format($user_class->quote); ?>" />
                </div>
                <div class="pure-control-group">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender"><?php
foreach ($genderOpts as $opt) {
    printf('<option value="%1$s"%2$s>%1$s</option>', $opt, $user_class->gender == $opt ? ' selected' : '');
}
?></select>
                </div>
            </fieldset>
            <div class="pure-controls">
                <button type="submit" name="submit" class="pure-button pure-button-primary">Save Preferences</button>
            </div>
        </form>
    </td>
</tr>
