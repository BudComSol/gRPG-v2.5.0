// Image popup/lightbox functionality
document.addEventListener('DOMContentLoaded', function() {
    // Track which element opened the modal for focus management
    let focusedElement = null;
    
    // Create modal overlay
    const modal = document.createElement('div');
    modal.id = 'image-modal';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-label', 'Image viewer');
    modal.style.display = 'none';
    modal.style.position = 'fixed';
    modal.style.zIndex = '10000';
    modal.style.left = '0';
    modal.style.top = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
    modal.style.cursor = 'pointer';
    modal.style.overflow = 'auto';
    
    // Create image element
    const modalImg = document.createElement('img');
    modalImg.id = 'modal-image';
    modalImg.style.margin = 'auto';
    modalImg.style.display = 'block';
    modalImg.style.maxWidth = '90%';
    modalImg.style.maxHeight = '90%';
    modalImg.style.position = 'absolute';
    modalImg.style.top = '50%';
    modalImg.style.left = '50%';
    modalImg.style.transform = 'translate(-50%, -50%)';
    
    modal.appendChild(modalImg);
    document.body.appendChild(modal);
    
    // Get all screenshot images
    const screenshots = document.querySelectorAll('img[src*="screenshots/"]');
    
    // Add click event to each screenshot
    screenshots.forEach(function(img) {
        img.style.cursor = 'pointer';
        img.style.transition = 'opacity 0.3s';
        
        // Add hover effect
        img.addEventListener('mouseenter', function() {
            this.style.opacity = '0.8';
        });
        
        img.addEventListener('mouseleave', function() {
            this.style.opacity = '1';
        });
        
        // Add click event to show full size
        img.addEventListener('click', function() {
            focusedElement = this;
            modal.style.display = 'block';
            modalImg.src = this.src;
            modalImg.alt = this.alt;
        });
    });
    
    // Close modal when clicking on the overlay (not the image)
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            this.style.display = 'none';
            if (focusedElement) {
                focusedElement.focus();
            }
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
            modal.style.display = 'none';
            if (focusedElement) {
                focusedElement.focus();
            }
        }
    });
});
