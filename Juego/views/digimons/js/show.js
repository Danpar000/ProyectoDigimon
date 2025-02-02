document.querySelector("#rotar").addEventListener("click", () => {
    const card = document.querySelector(".card__container");
    const topArea = document.querySelector("#card__topArea");
    const backwardsArea = document.querySelector("#card__backwardsArea");
    const options = document.querySelector(".options");

    const resetTransition = () => {
        card.style.transition = null;
    };

    if (card.classList.contains("card__container--rotate")) {
        card.classList.remove("card__container--rotate");
        card.classList.add("card__container--animated");
        void card.offsetWidth;
        card.style.transition = 'transform 1.6s ease';
        card.style.transform = 'scale(1) rotateY(0deg)';
        setTimeout(() => {
            backwardsArea.hidden = true;
            topArea.hidden = false;
        }, 500);

        options.style.transform = 'translateY(0)';
        card.addEventListener("transitionend", resetTransition, { once: true });

    } else {
        card.classList.add("card__container--rotate");
        card.classList.remove("card__container--animated");
        void card.offsetWidth;
        card.style.transition = 'transform 1s ease';
        card.style.transform = 'scale(1.5) rotateY(180deg)';
        setTimeout(() => {
            topArea.hidden = true;
            backwardsArea.hidden = false;
        }, 500);

        options.style.transform = `translateY(150%)`;
        card.addEventListener("transitionend", resetTransition, { once: true });
    }
});
