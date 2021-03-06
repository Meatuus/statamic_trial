<?php

namespace Statamic\Data\Taxonomies;

use Statamic\API\Config;
use Statamic\API\Helper;
use Statamic\API\Taxonomy;
use Statamic\API\Term;
use Statamic\Data\Content\Content;
use Statamic\Events\Stache\RepositoryItemInserted;
use Statamic\Events\Stache\RepositoryItemRemoved;
use Statamic\Stache\Stache;
use Statamic\Stache\Staches\TaxonomyStache;
use Statamic\Contracts\Data\Taxonomies\Taxonomy as TaxonomyContract;

class TermTracker
{
    /**
     * @var Stache
     */
    private $stache;
    /**
     * @var TaxonomyStache
     */
    private $taxonomyStache;

    /**
     * @param Stache $stache
     */
    public function __construct(Stache $stache)
    {
        $this->stache = $stache;
        $this->taxonomyStache = $stache->taxonomies;
    }

    /**
     * Register the listeners for the subscriber
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(RepositoryItemInserted::class, self::class.'@insert');
        $events->listen(RepositoryItemRemoved::class, self::class.'@remove');
    }

    /**
     * @param RepositoryItemInserted $event
     */
    public function insert(RepositoryItemInserted $event)
    {
        if ($event->item instanceof TaxonomyContract) {
            $this->updateLocalizedTermUris($event->item);
            return;
        }

        if (! $event->item instanceof Content) {
            return;
        }

        Taxonomy::all()->each(function ($taxonomy, $handle) use ($event) {
            if ($event->item->has($handle)) {
                try {
                    $this->addTerms($handle, $event->item);
                } catch (\Exception $e) {
                    \Log::debug('There was a problem adding taxonomy terms to data with ID ' . $event->item->id());
                    \Log::debug($e->getMessage() . "\n" . $e->getTraceAsString());
                }
            }
        });
    }

    private function addTerms($taxonomy, $item)
    {
        // Don't do anything if there aren't any terms.
        if (! $values = $item->get($taxonomy)) {
            return;
        }

        $this->taxonomyStache->syncAssociations(
            $item->id(),
            $taxonomy,
            Helper::ensureArray($item->get($taxonomy))
        );
    }

    private function updateLocalizedTermUris($taxonomy)
    {
        $terms = [];

        $this->taxonomyStache->clearLocalizedUris();

        // Collect all the terms that have had their slugs localized.
        foreach ($taxonomy->get('slugs', []) as $locale => $slugs) {
            foreach (Helper::ensureArray($slugs) as $slug => $localizedSlug) {
                $terms[] = $slug;
            }
        }

        // Now that we have the terms (not concerned with the locales
        // now) we can let the Stache sort out the respective URIs.
        foreach (collect($terms)->unique() as $term) {
            $this->taxonomyStache->addUris($taxonomy->basename(), $term);
        }
    }

    /**
     * @param RepositoryItemRemoved $event
     */
    public function remove(RepositoryItemRemoved $event)
    {
        $this->taxonomyStache->removeData($event->id);
    }
}
