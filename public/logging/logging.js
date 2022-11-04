function addSearchParameter(name, value) {
    const url = new URL(window.location.href);

    if(url.searchParams.has(name)) {
        url.searchParams.set(name, value);
    } else {
        url.searchParams.append(name, value);
    }

    window.location = url.toString();
}

window.onload = function() {
    const filterBox = document.getElementById('filter');

    filterBox.addEventListener('keypress', function(event) {
        if (event.key === "Enter") {
            addSearchParameter('filter', filterBox.value);
        }
    });

    document.querySelectorAll("td[id=ip]").forEach(function(ipTd) {
        let p = document.createElement('p');
        p.style.display = 'none';
        p.innerText = "Privacy Mode Enabled!";

        ipTd.appendChild(p);

        let childNodes = ipTd.childNodes;
        childNodes[0].style.display = 'none';
        childNodes[1].style.display = 'block';
    });

    document.querySelectorAll("button[id=Original]").forEach(function(button) {
        let buttonChildNodes = button.parentNode.childNodes;
        let decode = buttonChildNodes[4].innerText;
        decode = decode.replaceAll("¤tSave", "&amp;currentSave");
        decode = decodeURIComponent(decode);
        buttonChildNodes[4].innerText = decode;

        let encode = encodeURIComponent(decode);
        let pretty = decode.replaceAll('&amp;', '<br>');
        let privacy = decode.replaceAll(/Email=[^&]*/g, "Email=");
        privacy = privacy.replaceAll(/Pass=[^&]*/g, "Pass=");

        //alert(privacy);

        let p = document.createElement('p');
        p.style.display = 'none';
        p.innerText = encode;

        button.parentNode.appendChild(p);

        p = document.createElement('p');
        p.style.display = 'none';
        p.innerText = pretty;

        button.parentNode.appendChild(p);

        p = document.createElement('p');
        p.style.display = 'none';
        p.innerText = privacy;

        button.parentNode.appendChild(p);

        button.onclick = function(e) {
            let td = e.target.parentNode.childNodes;

            td[4].style.display = 'none';
            td[5].style.display = 'block';
            td[6].style.display = 'none';
            td[7].style.display = 'none';
        }

        buttonChildNodes = button.parentNode.childNodes;
        buttonChildNodes[4].style.display = 'none';
        buttonChildNodes[5].style.display = 'none';
        buttonChildNodes[6].style.display = 'none';
        buttonChildNodes[7].style.display = 'block';
    });

    document.querySelectorAll("button[id=Decode]").forEach(function(button) {
        button.onclick = function(e) {
            let td = e.target.parentNode.childNodes;

            td[4].style.display = 'block';
            td[5].style.display = 'none';
            td[6].style.display = 'none';
            td[7].style.display = 'none';
        }
    });

    document.querySelectorAll("button[id=Pretty]").forEach(function(button) {
        button.onclick = function(e) {
            let td = e.target.parentNode.childNodes;

            td[4].style.display = 'none';
            td[5].style.display = 'none';
            td[6].style.display = 'block';
            td[7].style.display = 'none';
        }
    });

    let checkbox = document.querySelector("input[name=privacy]");

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            document.querySelectorAll("button[id=Decode]").forEach(function(button) {
                let buttonChildNodes = button.parentNode.childNodes;
                buttonChildNodes[4].style.display = 'none';
                buttonChildNodes[5].style.display = 'none';
                buttonChildNodes[6].style.display = 'none';
                buttonChildNodes[7].style.display = 'block';
            });

            document.querySelectorAll("td[id=ip]").forEach(function(ipTd) {
                let ipTdChildNodes = ipTd.childNodes;
                ipTdChildNodes[0].style.display = 'none';
                ipTdChildNodes[1].style.display = 'block';
            });
        } else {
            document.querySelectorAll("button[id=Decode]").forEach(function(button) {
                let buttonChildNodes = button.parentNode.childNodes;
                buttonChildNodes[4].style.display = 'block';
                buttonChildNodes[5].style.display = 'none';
                buttonChildNodes[6].style.display = 'none';
                buttonChildNodes[7].style.display = 'none';
            });

            document.querySelectorAll("td[id=ip]").forEach(function(ipTd) {
                let ipTdChildNodes = ipTd.childNodes;
                ipTdChildNodes[0].style.display = 'block';
                ipTdChildNodes[1].style.display = 'none';
            });
        }
    });

}