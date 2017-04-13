var hueWrap = document.getElementById('hue-wrap');
var lumWrap = document.getElementById('lum-wrap');
var filter = document.getElementById('filter');
var bg = document.getElementById('selected-color');

var picker = {
    hues: {
        red: [255, 0, 0],
        rose: [255, 0, 127],
        magenta: [255, 0, 255],
        violet: [127, 0, 255],
        blue: [0, 0, 255],
        azure: [0, 127, 255],
        cyan: [0, 255, 255],
        aquamarine: [0, 255, 127],
        green: [0, 255, 0],
        chartreuse: [127, 255, 0],
        yellow: [255, 255, 0],
        orange: [255, 127, 0],
        gray: [127, 127, 127]
    },
    INTERVALS: [80, 70, 60, 50, 40, 30, 20],
    BWINTERVALS: [100, 85, 69, 53, 37, 21, 5]
};

for (var key in picker.hues) {
    hueWrap.innerHTML += '<div id="' + key + '" class="hue" style="background:' + rgbFormat(picker.hues[key]) + ';"></div>';
}

hueWrap.addEventListener('click', function (e) {
    var el = e.target.id;
    var s = document.getElementsByClassName('selected')[0];
    if (el != 'hue-wrap') {
        // var bg = e.target.style.background;
        // var match = bg.match(/\(([^)]+)\)/);
        // var newText = match && match[1];
        // var rgb = newText.split(',');
        if (s) {
            s.classList.remove('selected');
        }
        e.target.classList.add('selected');
        buildLums(lumVariants(picker.hues[el], el));
        changeColorBar(e.target.style.background);
    }
});

lumWrap.addEventListener('click', function (e) {
    var el = e.target.id;
    var s = document.getElementsByClassName('selected')[1];
    if (el != 'lum-wrap') {
        if (s) {
            s.classList.remove('selected');
        }
        e.target.classList.add('selected');
        changeColorBar(e.target.style.background)
    }
});

function changeColorBar(elRgb) {
    filter.style.background = 'linear-gradient(90deg, transparent, ' + elRgb + ' 50%)';
    filter.classList.add('trans');
    setTimeout(function () {
        bg.style.background = elRgb;
        filter.classList.remove('trans');
    }, 500);
}
//Initial background setting
document.getElementById('gray').click();

function buildLums(lums) {
    lumWrap.innerHTML = '';
    for (var i in lums) {
        var s = '';
        if (i == picker.INTERVALS.length) {
            s = 'selected';
        }
        lumWrap.innerHTML += '<div id="" class="lum ' + s + '" style="background:' + lums[i] + ';"></div>';
    }
}

function lumVariants(num, id) {
    var int = picker.INTERVALS;
    if (id === 'gray') {
        int = picker.BWINTERVALS;
    }
    var ILen = int.length;
    var l = 1 + (ILen * 2);
    var lumArray = [];

    for (var i = 0; i < l; i++) {
        if (i < ILen) {
            lumArray[i] = rgbFormat(darken(num, int[i]));
        } else if (i === ILen) {
            lumArray[i] = rgbFormat(num);
        } else {
            lumArray[i] = rgbFormat(lighten(num, int[i - (ILen + 1)]));
        }
    }
    return lumArray;
}

function darken(num, perc) {
    var rgb = [];
    for (var i = 0; i < 3; i++) {
        var t = 0;
        if (num[i] !== 0) {
            t = Math.round(num[i] - (num[i] * (perc / 100)));
            if (t < 0) {
                t = 0;
            }
        }
        rgb[i] = t;
    }
    return rgb;
}

function lighten(num, perc) {
    var rgb = [];
    for (var i = 0; i < 3; i++) {
        var t = 255;
        var n = 255 - num[i];
        if (num[i] !== 255) {
            t = Math.round(num[i] + (n - (n * ((perc - 5) / 100))));
            if (t > 255) {
                t = 255;
            }
        }
        rgb[i] = t;
    }
    return rgb;
}

function rgbFormat(num) {
    return 'rgb(' + num[0] + ',' + num[1] + ',' + num[2] + ')';
}


//OPEN/CLOSE
var editor = document.getElementById('editor');
var wrap = document.getElementById('picker');
var quick = document.getElementById('quick');

editor.addEventListener('click', function () {
    hueWrap.style.display = 'block';
    lumWrap.style.display = 'block';
    wrap.classList.remove('closeVert');
    setTimeout(function () {
        wrap.classList.remove('closeHorz');
        hueWrap.style.opacity = '1';
        lumWrap.style.opacity = '1';
    }, 1000);
});
quick.addEventListener('click', function () {
    hueWrap.style.opacity = '0';
    lumWrap.style.opacity = '0';
    wrap.classList.add('closeHorz');
    setTimeout(function () {
        hueWrap.style.display = 'none';
        lumWrap.style.display = 'none';
        wrap.classList.add('closeVert');
    }, 1000);
});