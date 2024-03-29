<?php

return [
    /*
     * These directories will be scanned for projectors and reactors. They
     * will be automatically registered to projectionist automatically.
     */
    'auto_discover_projectors_and_reactors' => [
        // Empty to turn off auto-discovery
    ],

    /*
     * Projectors are classes that build up projections. You can create them by
     * performing `php artisan event-projector:create-projector`. Projectors
     * can be registered in this array or a service provider.
     */
    'projectors' => [
        \App\Projectors\SquadCombatStatsProjector::class,
        \App\Projectors\HeroCombatStatsProjector::class,
        \App\Projectors\ItemCombatStatsProjector::class
    ],

    /*
     * Reactors are classes that handle side-effects. You can create them by performing
     * `php artisan event-sourcing:create-reactor`. When not using auto-discovery
     * Reactors can be registered in this array or a service provider.
     */
    'reactors' => [
        // App\Reactors\YourReactor::class
    ],

    /*
     * A queue is used to guarantee that all events get passed to the projectors in
     * the right order. Here you can set of the name of the queue.
     */
    'queue' => env('EVENT_PROJECTOR_QUEUE_NAME', null),

    /*
     * When a Projector or Reactor throws an exception the event Projectionist can catch it
     * so all other projectors and reactors can still do their work. The exception will
     * be passed to the `handleException` method on that Projector or Reactor.
     */
    'catch_exceptions' => env('EVENT_PROJECTOR_CATCH_EXCEPTIONS', false),

    /*
     * This class is responsible for storing events in the EloquentStoredEventRepository.
     * To add extra behaviour you can change this to a class of your own. It should
     * extend the \Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent model.
     */
    'stored_event_model' => Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent::class,

    /*
     * This class is responsible for storing events. To add extra behaviour you
     * can change this to a class of your own. The only restriction is that
     * it should implement \Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository.
     */
    'stored_event_repository' => Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository::class,

    /*
     * This class is responsible for storing snapshots. To add extra behaviour you
     * can change this to a class of your own. The only restriction is that
     * it should implement \Spatie\EventSourcing\Snapshots\EloquentSnapshotRepository.
     */
    'snapshot_repository' => Spatie\EventSourcing\Snapshots\EloquentSnapshotRepository::class,

    /*
     * This class is responsible for storing events in the EloquentSnapshotRepository.
     * To add extra behaviour you can change this to a class of your own. It should
     * extend the \Spatie\EventSourcing\Snapshots\EloquentSnapshot model.
     */
    'snapshot_model' => Spatie\EventSourcing\Snapshots\EloquentSnapshot::class,

    /*
     * This class is responsible for handling stored events. To add extra behaviour you
     * can change this to a class of your own. The only restriction is that
     * it should implement \Spatie\EventSourcing\StoredEvents\HandleDomainEventJob.
     */
    'stored_event_job' => Spatie\EventSourcing\StoredEvents\HandleStoredEventJob::class,

    /*
     * Similar to Relation::morphMap() you can define which alias responds to which
     * event class. This allows you to change the namespace or class names
     * of your events but still handle older events correctly.
     */
    'event_class_map' => [],

    /*
     * This class is responsible for serializing events. By default an event will be serialized
     * and stored as json. You can customize the class name. A valid serializer
     * should implement Spatie\EventSourcing\EventSerializers\EventSerializer.
     */
    'event_serializer' => Spatie\EventSourcing\EventSerializers\JsonEventSerializer::class,

    /*
     * These classes normalize and restore your events when they're serialized. They allow
     * you to efficiently store PHP objects like Carbon instances, Eloquent models, and
     * Collections. If you need to store other complex data, you can add your own normalizers
     * to the chain. See https://symfony.com/doc/current/components/serializer.html#normalizers
     */
    'event_normalizers' => [
        Spatie\EventSourcing\Support\CarbonNormalizer::class,
        Spatie\EventSourcing\Support\ModelIdentifierNormalizer::class,
        Symfony\Component\Serializer\Normalizer\DateTimeNormalizer::class,
        Symfony\Component\Serializer\Normalizer\ObjectNormalizer::class,
    ],

    /*
     * When replaying events, potentially a lot of events will have to be retrieved.
     * In order to avoid memory problems events will be retrieved as chunks.
     * You can specify the chunk size here.
     */
    'replay_chunk_size' => 1000,

    /*
     * In production, you likely don't want the package to auto-discover the event handlers
     * on every request. The package can cache all registered event handlers.
     * More info:
     * https://spatie.be/docs/laravel-event-sourcing/v4/advanced-usage/discovering-projectors-and-reactors#caching-discovered-projectors-and-reactors
     *
     * Here you can specify where the cache should be stored.
     */
    'cache_path' => storage_path('app/event-sourcing'),

    /*
     * When storable events are fired from aggregates roots, the package can fire off these
     * events as regular events as well.
     */

    'dispatch_events_from_aggregate_roots' => false,
];
