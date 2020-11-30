# dokuwiki-plugin-imagecdn
replace media file with remote url

## feature

* currently only supports absolute pagename. `{{:foo:bar:baz.(jpg|png)}}` -> `<img src="http://imagecdn.example.com/dokuwiki_media_mirror/foo/bar/baz.(jpg|png)">`

## todo
* make to work like normal `{{image file}}` using `fetch.php`
* support `float` rendering with spaces.
* obey ACL. even no effect in remote cdn.
