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
        \App\Projectors\UserProjector::class,
        \App\Projectors\SquadProjector::class,
        \App\Projectors\SquadEssenceProjector::class,
        \App\Projectors\SquadGoldProjector::class,
        \App\Projectors\SquadExperienceProjector::class,
        \App\Projectors\SquadFavorProjector::class,
        \App\Projectors\SquadHeroPostsProjector::class,
        \App\Projectors\SquadSlotsProjector::class,
        \App\Projectors\HeroProjector::class,
        \App\Projectors\ItemProjector::class,
        \App\Projectors\MeasurableProjector::class,
        \App\Projectors\CampaignProjector::class,
        \App\Projectors\PlayerSpiritProjector::class
    ],

    /*
     * Reactors are classes that handle side effects. You can create them by
     * performing `php artisan event-projector:create-reactor`. Reactors
     * can be registered in this array or a service provider.
     */
    'reactors' => [
        // App\Reactors\YourReactors::class
    ],

    /*
     * A queue is used to guarantee that all events get passed to the projectors in
     * the right order. Here you can set of the name of the queue.
     */
    'queue' => env('EVENT_PROJECTOR_QUEUE_NAME', null),

    /*
     * When a projector or reactor throws an exception the event projectionist can catch it
     * so all other projectors and reactors can still do their work. The exception will
     * be passed to the `handleException` method on that projector or reactor.
     */
    'catch_exceptions' => env('EVENT_PROJECTOR_CATCH_EXCEPTIONS', false),

    /*
     * This class is responsible for storing events. To add extra behaviour you
     * can change this to a class of your own. The only restriction is that
     * it should extend \Spatie\EventProjector\Models\StoredEvent.
     */
    'stored_event_model' => \Spatie\EventProjector\Models\StoredEvent::class,

    /*
     * This class is responsible for handle stored events. To add extra behaviour you
     * can change this to a class of your own. The only restriction is that
     * it should extend \Spatie\EventProjector\HandleStoredEventJob.
     */
    'stored_event_job' => \Spatie\EventProjector\HandleStoredEventJob::class,

    /*
     * This class is responsible for serializing events. By default an event will be serialized
     * and stored as json. You can customize the class name. A valid serializer
     * should implement Spatie\EventProjector\EventSerializers\Serializer.
     */
    'event_serializer' => \Spatie\EventProjector\EventSerializers\JsonEventSerializer::class,

    /*
     * When replaying events potentially a lot of events will have to be retrieved.
     * In order to avoid memory problems events will be retrieved in
     * a chuncked way. You can specify the chunk size here.
     */
    'replay_chunk_size' => 1000,
];
