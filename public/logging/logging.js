window.onload = function() {
    document.querySelectorAll("button[id=Original]").forEach(function(button) {
        var decode = button.parentNode.childNodes[4].innerHTML;
        var encode = encodeURIComponent(decode);
        var pretty = decode.replaceAll('&amp;', '<br>');

        var p = document.createElement('p');
        p.style.display = 'none';
        p.innerHTML = encode;

        button.parentNode.appendChild(p);

        var p = document.createElement('p');
        p.style.display = 'none';
        p.innerHTML = pretty;

        button.parentNode.appendChild(p);

        button.onclick = function(e) {
            var td = e.target.parentNode;

            td.childNodes[4].style.display = 'none';
            td.childNodes[5].style.display = 'block';
            td.childNodes[6].style.display = 'none';
        }
    });

    document.querySelectorAll("button[id=Decode]").forEach(function(button) {
        button.onclick = function(e) {
            var td = e.target.parentNode;

            td.childNodes[4].style.display = 'block';
            td.childNodes[5].style.display = 'none';
            td.childNodes[6].style.display = 'none';
        }
    });

    document.querySelectorAll("button[id=Pretty]").forEach(function(button) {
        button.onclick = function(e) {
            var td = e.target.parentNode;

            td.childNodes[4].style.display = 'none';
            td.childNodes[5].style.display = 'none';
            td.childNodes[6].style.display = 'block';
        }
    });
}