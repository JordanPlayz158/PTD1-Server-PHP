//import './utils.js';

$(function () {
    $("#header").load("/_static/html/header.html");
    $("#nav").load("/_static/html/nav.html");
    //$("#sidebar").load("../../_static/html/profile.html");
});

function loadProfile(callback) {
    $("#sidebar").load("/_static/html/profile.html", callback);
}

console.log("base.js loaded");
