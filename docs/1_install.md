# Install

Install with composer

````
{
    "require": {
        "lsv/magmi-datapump": "dev-master"
    }
}
````

## Install magmi

Magmi can be installed anywhere also out of the scope of your source.

Magmi cant be installed by composer, so its quite manual. Please refer to [Magmis own documentation](http://sourceforge.net/apps/mediawiki/magmi/index.php?title=Main_Page)

### Suggested installs

Now normally when Im using it I install some other things to make life easier.

* symfony/finder : For easier finding files that needs to be imported - Also better for image import
* symfony/filesystem : For easier to move files to the archive when its imported
* monolog/monolog : For better log management

````
{
    "require": {
        "symfony/finder": "2.3.*",
        "symfony/filesystem": "2.3.*",
        "monolog/monolog": "1.*",
    }
}
````

All my examples will make use of these suggestions