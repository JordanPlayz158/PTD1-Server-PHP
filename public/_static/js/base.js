//import './utils.js';

$(function () {
    $("#header").load("../../_static/html/header.html");
    $("#nav").load("../../_static/html/nav.html");
    $("#sidebar").load("../../_static/html/profile.html");
});

function loadProfile(callback) {
    $("#sidebar").load("../../_static/html/profile.html", callback);
}

function getCookie(cookieName) {
    let name = cookieName + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function setCookie(cookieName, cookieValue, expireDays) {
    const d = new Date();
    d.setTime(d.getTime() + (expireDays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/;secure;SameSite=Strict";
}

console.log("base.js loaded");