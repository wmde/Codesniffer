<?php

function noDocComment() {
}

/**
 * These are all fine.
 * @covers
 * @fixme
 * @inheritDoc
 * @license
 * @param
 * @return
 * @throws
 * @var
 * @todo
 * @uses
 */
function validTags() {
}

/**
 * These should all be reported.
 * @cover
 * @inheritdoc
 * @licence
 * @params
 * @property
 * @returns
 * @throw
 * @type
 * @use
 */
function lowerCaseTags() {
}

/**
 * Reporting should be case-insensitive for all-lowercase search patterns.
 * @Cover
 * @Inheritdoc
 * @Licence
 * @Params
 * @Property
 * @Returns
 * @Throw
 * @Type
 * @Use
 */
function upperCaseTags() {
}

/*
 * This is not a PHPDoc comment and should be ignored.
 * @cover
 * @inheritdoc
 * @licence
 * @params
 * @property
 * @returns
 * @throw
 * @type
 * @use
 */
function notADocComment() {
}

/**
 * @package Obsolete
 */
function disallowedTags() {
}

/**
 * @coverdefaultclass
 * @covernothing
 * @coversdefaultclass
 * @coversnothing
 * @dataprovider
 * @deprecate
 * @expectedexception
 * @expectedexceptioncode
 * @expectedexceptionmessage
 * @ToDo should be lowercase
 * @TODO: Should be "@todo" or "TODO:", but not both.
 */
function fixups() {
}
