<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        for ($i = 0; $i < count($program->getSeasons()); $i++) {
            $seasons = $program->getSeasons();
            $episodes = $seasons[$i]->getEpisodes();
            $programDuration = 0;
            for ($j = 0; $j < count($episodes); $j++) {
                $programDuration += $episodes[$j]->getDuration();
            }
        }
        return $programDuration;
    }
}