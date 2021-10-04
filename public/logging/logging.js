window.onload = function() {
    document.querySelectorAll("td[id=ip]").forEach(function(ipTd) {
        var p = document.createElement('p');
        p.style.display = 'none';
        p.innerHTML = "Privacy Mode Enabled!";

        ipTd.appendChild(p);

        ipTd.childNodes[0].style.display = 'none';
        ipTd.childNodes[1].style.display = 'block';
    });

    document.querySelectorAll("button[id=Original]").forEach(function(button) {
        var decode = button.parentNode.childNodes[4].innerHTML;
        decode = decode.replaceAll("¤tSave", "&amp;currentSave");
        button.parentNode.childNodes[4].innerHTML = decode;

        var encode = encodeURIComponent(decode);
        var pretty = decode.replaceAll('&amp;', '<br>');
        var privacy = decode.replaceAll(/Email=[^&]*/g, "Email=");
        privacy = privacy.replaceAll(/Pass=[^&]*/g, "Pass=");

        //alert(privacy);

        var p = document.createElement('p');
        p.style.display = 'none';
        p.innerHTML = encode;

        button.parentNode.appendChild(p);

        var p = document.createElement('p');
        p.style.display = 'none';
        p.innerHTML = pretty;

        button.parentNode.appendChild(p);

        var p = document.createElement('p');
        p.style.display = 'none';
        p.innerHTML = privacy;

        button.parentNode.appendChild(p);

        button.onclick = function(e) {
            var td = e.target.parentNode;

            td.childNodes[4].style.display = 'none';
            td.childNodes[5].style.display = 'block';
            td.childNodes[6].style.display = 'none';
            td.childNodes[7].style.display = 'none';
        }

        button.parentNode.childNodes[4].style.display = 'none';
        button.parentNode.childNodes[5].style.display = 'none';
        button.parentNode.childNodes[6].style.display = 'none';
        button.parentNode.childNodes[7].style.display = 'block';
    });

    document.querySelectorAll("button[id=Decode]").forEach(function(button) {
        button.onclick = function(e) {
            var td = e.target.parentNode;

            td.childNodes[4].style.display = 'block';
            td.childNodes[5].style.display = 'none';
            td.childNodes[6].style.display = 'none';
            td.childNodes[7].style.display = 'none';
        }
    });

    document.querySelectorAll("button[id=Pretty]").forEach(function(button) {
        button.onclick = function(e) {
            var td = e.target.parentNode;

            td.childNodes[4].style.display = 'none';
            td.childNodes[5].style.display = 'none';
            td.childNodes[6].style.display = 'block';
            td.childNodes[7].style.display = 'none';
        }
    });

    var checkbox = document.querySelector("input[name=privacy]");

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            document.querySelectorAll("button[id=Decode]").forEach(function(button) {
                button.parentNode.childNodes[4].style.display = 'none';
                button.parentNode.childNodes[5].style.display = 'none';
                button.parentNode.childNodes[6].style.display = 'none';
                button.parentNode.childNodes[7].style.display = 'block';
            });

            document.querySelectorAll("td[id=ip]").forEach(function(ipTd) {
                ipTd.childNodes[0].style.display = 'none';
                ipTd.childNodes[1].style.display = 'block';
            });
        } else {
            document.querySelectorAll("button[id=Decode]").forEach(function(button) {
                button.parentNode.childNodes[4].style.display = 'block';
                button.parentNode.childNodes[5].style.display = 'none';
                button.parentNode.childNodes[6].style.display = 'none';
                button.parentNode.childNodes[7].style.display = 'none';
            });

            document.querySelectorAll("td[id=ip]").forEach(function(ipTd) {
                ipTd.childNodes[0].style.display = 'block';
                ipTd.childNodes[1].style.display = 'none';
            });
        }
    });

}