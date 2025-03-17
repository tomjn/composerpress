# Composerpress

Retroactively attempts to create a `composer.json` for a WordPress site. On activation, a `composer.json` menu appears in the admin area under the Tools menu. This page shows what composerpress thinks a `composer.json` that will generate a website close or identical to the site the plugin is installed on might look like.

Includes support for detecting/reverse engineering composer packages via:

 - [packagist](https://packagist.org/)
 - [wpackagist](https://wpackagist.org/)*
 - private [mercurial](https://en.wikipedia.org/wiki/Mercurial) repositories
 - private [git](https://en.wikipedia.org/wiki/Git) repositories
 - private [svn](https://en.wikipedia.org/wiki/Apache_Subversion) repositories
 - non-composer plugins residing in git/svn/mercurial repositories

---

### Installation Notes

Install as a regular plugin and then run `composer install` on the `wp-content/plugins/composerpress` plugin folder to install dependencies.

For instructions on installing composer: <a href="http://getcomposer.org/doc/00-intro.md#installation-nix"> see here for *nix</a> and <a href="http://getcomposer.org/doc/00-intro.md#installation-windows">here for Windows</a>

---

## TODOs:

Currently ComposerPress does not perform any checking with wpackagist [#2](https://github.com/tomjn/composerpress/issues/2) (or similaries), leaving open these problems:

- when a _TextDomain_ doesn't match a _Plugins Name_ ?
- when a plugin doesn't match its _Folder Name_ ?
- when a _Plugin is Custom to a site_ (ie doesn't exist in the wp.org repo)?

### Keep in mind

1. At present, if composerpress can not find the source of a plugin, the choice will fall back on the default "composerpress" (to help you recognize them and act accordingly)

2. If you encounter a false positive try to contact plugin's author  and ask them to insert, at least a `Plugin URI:` in the header of the plugin; for example: these two plugins could collide if a URI plugin was not specified:
  - `Plugin URI: http://wordpress.org/extend/plugins/wp-less/` that will become something like `wpackagist-plugin/wp-less`
  - `Plugin URI: https://github.com/sanchothefat/wp-less/` that will become something like `sanchothefat/wp-less`

3. You can always give an help and contribute... :smile:

### Author articles

- [ComposerPress "Brief"](https://tomjn.com/2013/10/01/composerpress/)
- [Wordpress & Composer TLDR](https://tomjn.com/2015/09/03/wordpress-and-composer-tldr/)
- [ComposerPress "Project"](https://tomjn.com/projects/composerpress/)
