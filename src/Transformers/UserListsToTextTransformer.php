<?php

namespace SoerBot\Transformers;

use SoerBot\Transformers\Interfaces\Transform;

class UserListsToTextTransformer implements Transform
{
    /**
     * Text pattern for award.
     */
    const AWARD_ITEM = '%s (от %s)';

    /**
     * Text pattern for line.
     */
    const ITEM = '%s - рейтинг %d. Награды: %s.';

    /**
     * Response text if passed empty dataset.
     */
    const EMPTY_DATA = 'Данные отсутствуют';

    /**
     * Awards not found in line.
     */
    const AWARDS_NOT_FOUND = 'отсутствуют';

    /**
     * Transform user lists to plain text.
     *
     * @param $data
     * @return string
     */
    public function transform($data)
    {
        $lines = [];

        foreach ($data as $item) {
            $lines[] = $this->line($item);
        }

        return $this->toString($lines);
    }

    /**
     * Transform result to text.
     *
     * @param array $lines
     * @return string
     */
    private function toString(array $lines)
    {
        return empty($lines) ? self::EMPTY_DATA : implode("\n", $lines);
    }

    /**
     * @param $item
     * @return string
     */
    private function awards($item): string
    {
        if (empty($item['awards']) || !is_array($item['awards'])) {
            return self::AWARDS_NOT_FOUND;
        }

        return $this->glueAwards($item);
    }

    /**
     * @param $item
     * @return string
     */
    private function line($item)
    {
        return sprintf(self::ITEM, $item['id'], $item['rank'], $this->awards($item));
    }

    /**
     * @param $item
     * @return string
     */
    private function glueAwards($item): string
    {
        $awards = [];

        foreach ($item['awards'] as $award) {
            $awards[] = sprintf(self::AWARD_ITEM, $award['type'], $award['from']);
        }

        return implode(', ', $awards);
    }
}
