import { driver } from "driver.js";
import "driver.js/dist/driver.css";


console.log('here');

if (!localStorage.getItem("driverTourShown")) {
    const driverObj = driver({
        showProgress: true,
        animate: true,
        allowClose: true,
        smoothScroll: true,
        allowKeyboardControl: false,
        // overlayColor: 'red',
        steps: [
            {
                element: "#facebook",
                popover: { title: "Facebook influencers", description: "Description" },
            },
            {
                element: "#instagram",
                popover: { title: "Instagram influencers", description: "Description" },
            },
            {
                element: "#youtube",
                popover: { title: "Youtube influencers", description: "Description" },
            },
            {
                element: "#tiktok",
                popover: { title: "Tiktok influencers", description: "Description" },
            },
            {
                element: "#response_chart",
                popover: { title: "Response Chart", description: "Description" },
            },
            {
                element: "#logo-sidebar",
                popover: { title: "Side Menu", description: "Description" },
            },
            {
                element: "#notification_bell",
                popover: { title: "Notification", description: "Description" },
            },
            {
                element: "#profile_avatar",
                popover: { title: "Profile", description: "Description" },
            },
            {
                element: "#start_search",
                popover: { title: "Start", description: "Description" },
            },
        ],
        onCloseClick: (element, step, options) => {
            localStorage.setItem("driverTourShown", "true");
            driverObj.destroy();
        },
        onDestroyStarted: () => {
            if (!driverObj.hasNextStep() || confirm("Are you sure?")) {
                localStorage.setItem("driverTourShown", "true");
                driverObj.destroy();
                // window.scrollTo({ top: 0, behavior: "smooth" });
            }
        },
        onDestroyed: () => {
            setTimeout(() => {
                location.reload();
            }, 100); 
        }
        
    });

    // Start the tour
    driverObj.drive();
}
// 
// localStorage.removeItem("driverTourShown");

