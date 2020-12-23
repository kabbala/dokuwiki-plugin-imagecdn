# Plugin:Imagecdn
replace media file with remote url.

dokuwiki plugin type: Syntax.

## feature

* currently only supports absolute pagename begins with colon(`:`). `{{:foo:bar:baz.(jpg|png)}}` -> `<img src="http://imagecdn.example.com/dokuwiki_media_mirror/foo/bar/baz.(jpg|png)">`

## problem

* plugin:Move does not recognize image tag.

## todo
* align(float) support in external mode.
* obey ACL. even if no effect in remote cdn.
* `namespace` option(include/exclude)
* UTF8 character?

## note
* If you don't need to use DokuWiki's Media Manager, Using **InterWiki** function is good choice to link remote image. like `[[imagecdn>foo/bar.jpg]]`
