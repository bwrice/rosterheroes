<?php


namespace App\Admin\Content\Actions;


use App\Admin\Content\Sources\ContentSource;
use Illuminate\Support\Collection;

class UpdateContent
{
    public function execute(ContentSource $updatedSource, Collection $content, string $contentPath)
    {
        $matchingKey = $content->search(function (ContentSource $contentSource) use ($updatedSource) {
            return (string) $contentSource->getUuid() === (string) $updatedSource->getUuid();
        });

        if ($matchingKey === false) {
            throw new \Exception("Content Source with uuid: " . $updatedSource->getUuid() . " not found in sources");
        }

        $updatedContent = $content->replace([$matchingKey => $updatedSource]);
        $lastUpdated = now()->timestamp;

        file_put_contents($contentPath, json_encode([
            'last_updated' => $lastUpdated,
            'data' => $updatedContent->toArray()
        ], JSON_PRETTY_PRINT));
    }
}
