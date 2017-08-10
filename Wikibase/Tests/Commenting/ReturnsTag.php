<?php

function noDocComment() {
}

/**
 * @return void This is fine.
 */
function validReturnTag() {
}

/**
 * @returns void This should be reported for using "@returns" with an "s" instead of "@return".
 */
function lowerCaseReturnsTag() {
}

/**
 * @Returns void Reporting should be case-insensitive.
 */
function upperCaseReturnsTag() {
}

/*
 * @returns void This is not a PHPDoc comment and should be ignored.
 */
function notADocComment() {
}
