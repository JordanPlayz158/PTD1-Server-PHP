//<![CDATA[
var owa_baseUrl = 'https://analytics.jordanplayz158.xyz/';
var owa_cmds = owa_cmds || [];
owa_cmds.push(['setSiteId', '0ac5eff1eddd4fb8b8809cbfc42dc329']);
owa_cmds.push(['trackPageView']);
owa_cmds.push(['trackClicks']);
owa_cmds.push(['trackDomStream']);

function startTracking() {
    if (Cookies.get('tracking') === 'yes') {
        <!-- Start Open Web Analytics Tracker -->
        (function () {
            var _owa = document.createElement('script');
            _owa.type = 'text/javascript';
            _owa.async = true;
            owa_baseUrl = ('https:' == document.location.protocol ? window.owa_baseSecUrl || owa_baseUrl.replace(/http:/, 'https:') : owa_baseUrl);
            _owa.src = owa_baseUrl + 'modules/base/dist/owa.tracker.js';
            var _owa_s = document.getElementsByTagName('script')[0];
            _owa_s.parentNode.insertBefore(_owa, _owa_s);
        }());
        //]]>
        <!-- End Open Web Analytics Code -->
    }
}

window.onload = () => {
    const cookieScript = document.createElement('script');
    cookieScript.src = '/_static/js/js.cookie.js';

    cookieScript.onload = () => {
        if(Cookies.get('tracking') === undefined) {
            const div = document.createElement('div');
            div.style.border = '2px solid';
            div.style.borderRadius = '25px';
            div.style.backgroundColor = 'white';
            div.style.padding = '0 0 10px 0';

            div.style.position = 'fixed';
            div.style.bottom = '0';
            div.style.left = '0';
            div.style.right = '0';

            div.style.textAlign = 'center';

            const text = document.createElement('p');
            text.innerText = 'Do you give the site owner (JordanPlayz158#0090) permission to track your activity on the site?';

            const yesButton = document.createElement('button');
            yesButton.innerText = 'Yes';
            yesButton.onclick = () => {
                Cookies.set('tracking', 'yes');
                window.location.reload();
            }

            const noButton = document.createElement('button');
            noButton.innerText = 'No';
            noButton.onclick = () => {
                Cookies.set('tracking', 'no');
                window.location.reload();
            }

            div.append(text, yesButton, noButton);

            document.body.append(div);
        }

        startTracking();
    }

    document.head.appendChild(cookieScript);
}
