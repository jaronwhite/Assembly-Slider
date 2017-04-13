//TO DO :: Add logic for speed and controls once DB structure is updated
//vars
var loader = document.getElementById('as_loader');
var slides = document.getElementsByClassName('as_slide');
var slideCount = slides.length;
var current = slides[0];

window.addEventListener('load', function () {
    function fade() {
        loader.classList.remove('current');
        current.classList.add('current');
        loader.style.display = "none";
    }

    setTimeout(fade, 1000);
    if (slideCount > 1) {
        slider();
    }
});

/**
 * Controls the transition of slides.
 * Only called if there is more than a single slide in the slider.
 */
function slider() {
//vars
//     var prev = document.getElementById('as_prev');
//     var next = document.getElementById('as_next');
    var place = 0;
    var controls = document.getElementById('as_controls');
    var autoplay;
    var playing;
    createSelectors();
    var selectors = document.getElementsByClassName('as_selector');
    play();

//events
//     prev.addEventListener('click', left);
//     next.addEventListener('click', right);
    for (var i = 0; i < selectors.length; i++) {
        selectors[i].addEventListener('click', select);
    }


//functions
    function select() {
        stop();
        selectors[place].classList.remove('selected');
        place = this.id.substring(7, 8);
        current.classList.remove('current');
        current = slides[place];
        current.classList.add('current');
        selectors[place].classList.add('selected');
        // setTimeout(play, 10000); Add a restart option
    }

    function navigate(direction) {
        reStart();
        selectors[place].classList.remove('selected');
        place += direction;
        current.classList.remove('current');

        if (direction === 1 && !slides[place]) {
            place = 0;
        }
        if (direction === -1 && !slides[place]) {
            place = slideCount - 1;
        }
        current = slides[place];
        current.classList.add('current');
        selectors[place].classList.add('selected');
    }

    function play() {
        autoplay = setInterval(right, 6000);
        playing = true;
    }

    function stop() {
        clearInterval(autoplay);
        playing = false;
    }

    function reStart() {
        stop();
        play();
    }

    function left() {
        navigate(-1);
    }

    function right() {
        navigate(1);
    }

    function createSelectors() {
        for (var i = 0; i < slideCount; i++) {
            // prev.style.display = "block";
            // next.style.display = "block";
            var t = document.createElement('div');
            t.classList.add('as_selector');
            if (i === 0) {
                t.classList.add('selected');
            }
            t.id = 'control' + i;
            controls.appendChild(t);
        }
    }
}