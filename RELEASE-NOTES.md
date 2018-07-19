# Wikibase CodeSniffer standards changelog

## 0.5.0 (dev)

* Updated the base MediaWiki rule set from 16.0.1 to 19.0.0. This adds the following sniffs:
	* `MediaWiki.Commenting.LicenseComment`
	* `MediaWiki.Commenting.PhpunitAnnotations`
	* `MediaWiki.PHP71Features.NullableType`
	* `MediaWiki.PHP71Features.VoidReturnType`
* Enabled `MediaWiki.Commenting.MissingCovers`.
* Made `Wikibase.Namespaces.UnusedUse` aware of PHP7 return types.
* Added auto-fix for PHPDoc tags that end with a colon, e.g. `@todo:`.
* Added capitalization and spelling fixes for more PHPDoc tags:
	* `@covers…`
	* `@dataProvider`
	* `@deprecated`
	* `@expectedException…`
	* `@expected…`
* Sniff `Wikibase.Commenting.ClassLevelDocumentation` now support parameter `license` which will enforce presence of correct value in `@license` tag

## 0.4.1 (2018-03-23)

* Added `PHPUnit\Framework\TestCase` to the exceptions `Wikibase.Namespaces.FullQualifiedClassName`
  allows.
* `Wikibase.Namespaces.UnusedUse` can't confuse method with class names any more.

## 0.4.0 (2018-03-07)

* Added custom `Wikibase.Namespaces.FullQualifiedClassName` sniff.
* Updated the base MediaWiki rule set from 15.0.0 to 16.0.1. This adds the following sniff:
	* `MediaWiki.Usage.AssignmentInReturn`
* Removed `PSR1.Methods.CamelCapsMethodName` (redundant to
  `MediaWiki.NamingConventions.LowerCamelFunctionsName`).

## 0.3.0 (2018-02-12)

* Updated the base MediaWiki rule set from 0.8.1 to 15.0.0. This adds the following sniffs:
	* `Generic.Files.OneObjectStructurePerFile` (replaces `Generic.Files.OneClass…`, `…Interface…`,
	  and `…TraitPerFile`)
	* `Generic.PHP.BacktickOperator`
	* `Generic.PHP.DiscourageGoto` (replaces `MediaWiki.Usage.GotoUsage`)
	* `MediaWiki.AlternativeSyntax.LeadingZeroInFloat`
	* `MediaWiki.AlternativeSyntax.PHP7UnicodeSyntax`
	* `MediaWiki.AlternativeSyntax.ShortCastSyntax`
	* `MediaWiki.Files.ClassMatchesFilename`
	* `MediaWiki.Usage.DeprecatedConstantUsage`
	* `MediaWiki.Usage.ForbiddenFunctions`
	* `MediaWiki.Usage.ReferenceThis`
	* `MediaWiki.Usage.ScalarTypeHintUsage`
	* `MediaWiki.VariableAnalysis.ForbiddenGlobalVariables`
	* `MediaWiki.WhiteSpace.OpeningKeywordParenthesis`
* Added `@package` to the disallowed PHPDoc tags

## 0.2.0 (2017-10-13)

* Added custom sniffs:
	* `Wikibase.Commenting.ClassLevelDocumentation`
	* `Wikibase.Commenting.DisallowedDocTags`
	* `Wikibase.Commenting.RedundantVarName`
	* `Wikibase.Namespaces.UnnecessaryUse`
	* `Wikibase.Namespaces.UnusedUse`
	* `Wikibase.Usage.InArrayUsage`
* Updated the base MediaWiki rule set from 0.7.x to 0.8.1. This adds the following sniffs:
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
