

// notification.js
export default function notification() {
    return {
        
        notify(message) {
            Toastify({
                text: message,
                duration: 3000, // 3 seconds
                gravity: "top", // top or bottom
                position: "right", // left, center, right
                // backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
            }).showToast();
        }
    }
}
