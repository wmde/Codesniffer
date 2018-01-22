# Wikibase CodeSniffer standards changelog

## 0.3.0 (dev)

* Updated the base MediaWiki rule set from 0.8 to 0.10.1. This adds the following sniffs:
	* `Generic.PHP.BacktickOperator`
	* `MediaWiki.AlternativeSyntax.PHP7UnicodeSyntax`
	* `MediaWiki.AlternativeSyntax.ShortCastSyntax`
	* `MediaWiki.Usage.ReferenceThis`

## 0.2.0 (2017-10-13)

* Added custom sniffs:
	* `Wikibase.Commenting.ClassLevelDocumentation`
	* `Wikibase.Commenting.DisallowedDocTags`
	* `Wikibase.Commenting.RedundantVarName`
	* `Wikibase.Namespaces.UnnecessaryUse`
	* `Wikibase.Namespaces.UnusedUse`
	* `Wikibase.Usage.InArrayUsage`
* Updated the base MediaWiki rule set from 0.7 to 0.8.1. This adds the following sniffs:
	* `Generic.Formatting.NoSpaceAfterCast`
	* `MediaWiki.ExtraCharacters.ParenthesesAroundKeyword`
	* `MediaWiki.NamingConventions.LowerCamelFunctionsName`
	* `MediaWiki.Usage.DbrQueryUsage`
	* `MediaWiki.Usage.ExtendClassUsage`
	* `MediaWiki.Usage.SuperGlobalsUsage`
	* `MediaWiki.WhiteSpace.SpaceBeforeClassBrace`
	* `MediaWiki.WhiteSpace.SpaceBeforeControlStructureBrace`
	* `PSR2.Methods.FunctionClosingBrace`
* Added `Squiz.Operators.ValidLogicalOperators`
* Added `Squiz.WhiteSpace.CastSpacing`

## 0.1.0 (2017-04-26)

* Initial tagged release.
