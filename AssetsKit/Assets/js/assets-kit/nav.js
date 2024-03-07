let openedNavbar = null;

const getNavbarButtonEventListener = (nav) => {
    return function(){

        if (openedNavbar)
        {
            openedNavbar.classList.remove("active");
            if (nav !== openedNavbar)
                nav.classList.add("active");
            openedNavbar = null;
        }
        else
        {
            nav.classList.add("active");
            openedNavbar = nav;
        }
    };
}

const refreshMobileAccessibilityMode = async ()=>{
    const isMobile = document.body.classList.contains("is-mobile");

    if (!isMobile)
    {
        document.querySelectorAll(".navbar").forEach(nav => {
            nav.querySelector(".navbar-button")?.remove();
        });
        return;
    }

    let lastButtonBottom = 0;
    document.querySelectorAll(".navbar").forEach(nav => {

        let button = document.createElement("div");
        button.classList = "navbar-button";
        button.innerHTML = svg('list', 32)

        button.style.top = lastButtonBottom + "px";
        nav.appendChild(button);

        let box = button.getBoundingClientRect();
        lastButtonBottom = (box.top + box.height*1.1);

        button.addEventListener("click", getNavbarButtonEventListener(nav));
    });
}

document.addEventListener("DOMContentLoaded", refreshMobileAccessibilityMode);
document.addEventListener("mobileModeSwitched", refreshMobileAccessibilityMode);