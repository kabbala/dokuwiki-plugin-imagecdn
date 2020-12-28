# Plugin:Imagecdn
change media links to external CDN URLs to reduce traffic.

plugin type: Syntax.

## feature

* only supports absolute pagename begins with colon(`:`). `{{:foo:bar:baz.(jpg|png)}}`

## problem

* plugin:Move does not recognize image tag.

## todo
* MIME check for security.
* align(float) support in external mode.
* obey ACL. even if no effect in remote cdn.
* `namespace` option(include/exclude)
* UTF8 character?

## note
* If you don't need to use DokuWiki's Media Manager, Using **InterWiki** function is good choice to link remote image. like `[[imagecdn>foo/bar.jpg]]`
