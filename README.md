# Inboxly Receiver

This package used as part of the Inboxly service.

Explore sites to find feeds and fetch feeds with last entries.

## Install

You can install the package via composer:

```bash
composer require inboxly/receiver
```

## Usage

### Explore site to find feeds

```php
<?php

use Inboxly\Receiver\Managers\ExplorerManager;

class ExploreController
{
    public function explore(ExplorerManager $manager, string $site){
        $result = $manager->explore($site, 'rss');
        
        /** @var \Inboxly\Receiver\Sources\Rss\RssParameters $parameters */
        foreach ($result as $parameters) {
            dump("Found feed: $parameters->url");
        }
    }
}
```


### Fetch feed with entries

```php
<?php

use Inboxly\Receiver\Contracts\Parameters;
use Inboxly\Receiver\Managers\FetcherManager;

class FetchController
{
    public function fetch(FetcherManager $manager, Parameters $parameters){
        $feeds = $manager->fetch($parameters);

        /** @var \Inboxly\Receiver\Feed $feed */
        foreach ($feeds as $feed) {
            dump("Fetched feed: $feed->name");
            
            /** @var \Inboxly\Receiver\Entry $entry */
            foreach ($feed->entries as $entry) {
                dump("Entry in feed: $entry->name");
            }
        }
    }
}
```


## Testing

Run the tests with:

```bash
composer test
```


## Credits

- [Sento Sango](https://github.com/sentosango)
- [All Contributors](../../contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
