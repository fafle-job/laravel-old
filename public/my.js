// Example using HTTP POST operation
/*
"use strict";
var page = require('webpage').create(),
    server = 'http://www.natashaclub.com/member.php',
    data = 'ID=1000847429&_r=/member.php&Password=qwer123';

page.open(server, 'post', data, function (status) {
	
    if (status !== 'success') {
        console.log('Unable to post!');
    } else {
        console.log(page.content);
    }
    phantom.exit();
});*/
console.log('Hello, world!');
phantom.exit();

