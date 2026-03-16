<?php

namespace Database\Seeders;

use App\Models\Episode;
use App\Models\Part;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JoJoSeeder extends Seeder
{
    public function run(): void
    {
        $all_data = [
            [
                'number' => 1,
                'title' => 'Phantom Blood',
                'release_year' => 2012,
                'summary' => 'In late 19th-century England, the young Jonathan Joestar meets his new adopted brother Dio Brando, who only wants to usurp Jonathan as heir to the Joestar family. When Dio\'s attempts are thwarted, he uses an ancient Stone Mask that transforms him into a vampire.',
                'trailer_url' => 'https://www.youtube.com/watch?v=DT0yV2ZYrBQ',
                'episodes' => [
                    ['title' => 'Dio the Invader', 'air_date' => 'October 6, 2012'],
                    ['title' => 'A Letter from the Past', 'air_date' => 'October 13, 2012'],
                    ['title' => 'Youth with Dio', 'air_date' => 'October 20, 2012'],
                    ['title' => 'Overdrive', 'air_date' => 'October 27, 2012'],
                    ['title' => 'The Dark Knights', 'air_date' => 'November 3, 2012'],
                    ['title' => 'Tomorrow\'s Courage', 'air_date' => 'November 10, 2012'],
                    ['title' => 'Sorrowful Successor', 'air_date' => 'November 17, 2012'],
                    ['title' => 'Bloody Battle! JoJo & Dio', 'air_date' => 'November 24, 2012'],
                    ['title' => 'The Final Ripple!', 'air_date' => 'December 1, 2012'],
                ],
            ],
            [
                'number' => 2,
                'title' => 'Battle Tendency',
                'release_year' => 2012,
                'summary' => 'In 1938 New York City, Jonathan\'s grandson Joseph Joestar must master the power of Hamon to defeat the Pillar Men, ancient humanoid beings who created the Stone Mask.',
                'trailer_url' => 'https://www.youtube.com/watch?v=akrEeCatKg4',
                'episodes' => [
                    ['title' => 'New York\'s JoJo', 'air_date' => 'December 8, 2012'],
                    ['title' => 'The Game Master', 'air_date' => 'December 15, 2012'],
                    ['title' => 'The Pillar Man', 'air_date' => 'December 22, 2012'],
                    ['title' => 'JoJo vs. the Ultimate Lifeform', 'air_date' => 'January 5, 2013'],
                    ['title' => 'Ultimate Warriors from Ancient Times', 'air_date' => 'January 12, 2013'],
                    ['title' => 'A Hero\'s Proof', 'air_date' => 'January 19, 2013'],
                    ['title' => 'Lisa Lisa, Hamon Coach', 'air_date' => 'January 26, 2013'],
                    ['title' => 'The Deeper Plan', 'air_date' => 'February 2, 2013'],
                    ['title' => 'Von Stroheim\'s Revenge', 'air_date' => 'February 9, 2013'],
                    ['title' => 'A Race Toward the Brink', 'air_date' => 'February 16, 2013'],
                    ['title' => 'Young Caesar', 'air_date' => 'February 23, 2013'],
                    ['title' => 'A Hundred Against Two', 'air_date' => 'March 2, 2013'],
                    ['title' => 'A True Warrior', 'air_date' => 'March 9, 2013'],
                    ['title' => 'The Warrior of Wind', 'air_date' => 'March 16, 2013'],
                    ['title' => 'The Ties That Bind JoJo', 'air_date' => 'March 23, 2013'],
                    ['title' => 'The Birth of a Superbeing', 'air_date' => 'March 30, 2013'],
                    ['title' => 'The Ascendant One', 'air_date' => 'April 6, 2013'],
                ],
            ],
            [
                'number' => 3,
                'title' => 'Stardust Crusaders',
                'release_year' => 2014,
                'summary' => 'Jotaro Kujo travels to Egypt to defeat the resurrected Dio and save his mother.',
                'trailer_url' => 'https://www.youtube.com/watch?v=XKpD3WtFRCs',
                'episodes' => [
                    ['title' => 'The Man Possessed by an Evil Spirit', 'air_date' => 'April 5, 2014'],
                    ['title' => 'Who Will Be the Judge!?', 'air_date' => 'April 12, 2014'],
                    ['title' => 'The Curse of Dio', 'air_date' => 'April 19, 2014'],
                    ['title' => 'Tower of Gray', 'air_date' => 'April 26, 2014'],
                    ['title' => 'Silver Chariot', 'air_date' => 'May 3, 2014'],
                    ['title' => 'Dark Blue Moon', 'air_date' => 'May 10, 2014'],
                    ['title' => 'Strength', 'air_date' => 'May 17, 2014'],
                    ['title' => 'The Devil', 'air_date' => 'May 24, 2014'],
                    ['title' => 'Yellow Temperance', 'air_date' => 'May 31, 2014'],
                    ['title' => 'The Emperor and the Hanged Man, Part 1', 'air_date' => 'June 7, 2014'],
                    ['title' => 'The Emperor and the Hanged Man, Part 2', 'air_date' => 'June 14, 2014'],
                    ['title' => 'The Empress', 'air_date' => 'June 21, 2014'],
                    ['title' => 'Wheel of Fortune', 'air_date' => 'June 28, 2014'],
                    ['title' => 'Justice, Part 1', 'air_date' => 'July 5, 2014'],
                    ['title' => 'Justice, Part 2', 'air_date' => 'July 12, 2014'],
                    ['title' => 'The Lovers, Part 1', 'air_date' => 'July 19, 2014'],
                    ['title' => 'The Lovers, Part 2', 'air_date' => 'July 26, 2014'],
                    ['title' => 'The Sun', 'air_date' => 'August 2, 2014'],
                    ['title' => 'Death 13, Part 1', 'air_date' => 'August 9, 2014'],
                    ['title' => 'Death 13, Part 2', 'air_date' => 'August 16, 2014'],
                    ['title' => 'Judgement, Part 1', 'air_date' => 'August 23, 2014'],
                    ['title' => 'Judgement, Part 2', 'air_date' => 'August 30, 2014'],
                    ['title' => 'High Priestess, Part 1', 'air_date' => 'September 6, 2014'],
                    ['title' => 'High Priestess, Part 2', 'air_date' => 'September 13, 2014'],
                    ['title' => 'Iggy the Fool and Geb\'s N\'Doul, Part 1', 'air_date' => 'January 10, 2015'],
                    ['title' => 'Iggy the Fool and Geb\'s N\'Doul, Part 2', 'air_date' => 'January 17, 2015'],
                    ['title' => 'Khnum\'s Oingo and Thoth\'s Boingo', 'air_date' => 'January 24, 2015'],
                    ['title' => 'Anubis, Part 1', 'air_date' => 'January 31, 2015'],
                    ['title' => 'Anubis, Part 2', 'air_date' => 'February 7, 2015'],
                    ['title' => 'Bastet\'s Mariah, Part 1', 'air_date' => 'February 14, 2015'],
                    ['title' => 'Bastet\'s Mariah, Part 2', 'air_date' => 'February 21, 2015'],
                    ['title' => 'Set\'s Alessi, Part 1', 'air_date' => 'February 28, 2015'],
                    ['title' => 'Set\'s Alessi, Part 2', 'air_date' => 'March 7, 2015'],
                    ['title' => 'D\'Arby the Gambler, Part 1', 'air_date' => 'March 14, 2015'],
                    ['title' => 'D\'Arby the Gambler, Part 2', 'air_date' => 'March 21, 2015'],
                    ['title' => 'Hol Horse and Boingo, Part 1', 'air_date' => 'March 28, 2015'],
                    ['title' => 'Hol Horse and Boingo, Part 2', 'air_date' => 'April 4, 2015'],
                    ['title' => 'The Guardian of Hell, Pet Shop, Part 1', 'air_date' => 'April 11, 2015'],
                    ['title' => 'The Guardian of Hell, Pet Shop, Part 2', 'air_date' => 'April 18, 2015'],
                    ['title' => 'D\'Arby the Player, Part 1', 'air_date' => 'April 25, 2015'],
                    ['title' => 'D\'Arby the Player, Part 2', 'air_date' => 'May 2, 2015'],
                    ['title' => 'The Miasma of the Void, Vanilla Ice, Part 1', 'air_date' => 'May 9, 2015'],
                    ['title' => 'The Miasma of the Void, Vanilla Ice, Part 2', 'air_date' => 'May 16, 2015'],
                    ['title' => 'The Miasma of the Void, Vanilla Ice, Part 3', 'air_date' => 'May 23, 2015'],
                    ['title' => 'Dio\'s World, Part 1', 'air_date' => 'May 30, 2015'],
                    ['title' => 'Dio\'s World, Part 2', 'air_date' => 'June 6, 2015'],
                    ['title' => 'Dio\'s World, Part 3', 'air_date' => 'June 13, 2015'],
                    ['title' => 'Long Journey Farewell, My Friends', 'air_date' => 'June 20, 2015'],
                ],
            ],
            [
                'number' => 4,
                'title' => 'Diamond Is Unbreakable',
                'release_year' => 2016,
                'summary' => 'Josuke Higashikata protects the town of Morioh from a serial killer with a Stand.',
                'trailer_url' => 'https://www.youtube.com/watch?v=t8WxJVBhiTY',
                'episodes' => [
                    ['title' => 'Jotaro Kujo! Meets Josuke Higashikata', 'air_date' => 'April 2, 2016'],
                    ['title' => 'Josuke Higashikata! Meets Angelo', 'air_date' => 'April 9, 2016'],
                    ['title' => 'The Nijimura Brothers, Part 1', 'air_date' => 'April 16, 2016'],
                    ['title' => 'The Nijimura Brothers, Part 2', 'air_date' => 'April 23, 2016'],
                    ['title' => 'The Nijimura Brothers, Part 3', 'air_date' => 'April 30, 2016'],
                    ['title' => 'Koichi Hirose (Echoes)', 'air_date' => 'May 7, 2016'],
                    ['title' => 'Toshikazu Hazamada (Surface)', 'air_date' => 'May 14, 2016'],
                    ['title' => 'Yukako Yamagishi Falls in Love, Part 1', 'air_date' => 'May 21, 2016'],
                    ['title' => 'Yukako Yamagishi Falls in Love, Part 2', 'air_date' => 'May 28, 2016'],
                    ['title' => 'Let\'s Go Eat Some Italian Food', 'air_date' => 'June 4, 2016'],
                    ['title' => 'Red Hot Chili Pepper, Part 1', 'air_date' => 'June 11, 2016'],
                    ['title' => 'Red Hot Chili Pepper, Part 2', 'air_date' => 'June 18, 2016'],
                    ['title' => 'We Picked Up Something Crazy!', 'air_date' => 'June 25, 2016'],
                    ['title' => 'Let\'s Go to the Manga Artist\'s House, Part 1', 'air_date' => 'July 2, 2016'],
                    ['title' => 'Let\'s Go to the Manga Artist\'s House, Part 2', 'air_date' => 'July 9, 2016'],
                    ['title' => 'Let\'s Go Hunting!', 'air_date' => 'July 16, 2016'],
                    ['title' => 'Rohan Kishibe\'s Adventure', 'air_date' => 'July 23, 2016'],
                    ['title' => 'Shigechi\'s Harvest, Part 1', 'air_date' => 'July 30, 2016'],
                    ['title' => 'Shigechi\'s Harvest, Part 2', 'air_date' => 'August 6, 2016'],
                    ['title' => 'Yukako Yamagishi Dreams of Cinderella', 'air_date' => 'August 13, 2016'],
                    ['title' => 'Yoshikage Kira Just Wants to Live Quietly, Part 1', 'air_date' => 'August 20, 2016'],
                    ['title' => 'Yoshikage Kira Just Wants to Live Quietly, Part 2', 'air_date' => 'August 27, 2016'],
                    ['title' => 'Sheer Heart Attack, Part 1', 'air_date' => 'September 3, 2016'],
                    ['title' => 'Sheer Heart Attack, Part 2', 'air_date' => 'September 10, 2016'],
                    ['title' => 'Atom Heart Father', 'air_date' => 'September 17, 2016'],
                    ['title' => 'Janken Boy Is Coming!', 'air_date' => 'September 24, 2016'],
                    ['title' => 'I\'m an Alien', 'air_date' => 'October 1, 2016'],
                    ['title' => 'Highway Star, Part 1', 'air_date' => 'October 8, 2016'],
                    ['title' => 'Highway Star, Part 2', 'air_date' => 'October 15, 2016'],
                    ['title' => 'Cats Love Yoshikage Kira', 'air_date' => 'October 22, 2016'],
                    ['title' => 'July 15th (Thurs), Part 1', 'air_date' => 'October 29, 2016'],
                    ['title' => 'July 15th (Thurs), Part 2', 'air_date' => 'November 5, 2016'],
                    ['title' => 'July 15th (Thurs), Part 3', 'air_date' => 'November 12, 2016'],
                    ['title' => 'July 15th (Thurs), Part 4', 'air_date' => 'November 19, 2016'],
                    ['title' => 'Another One Bites the Dust, Part 1', 'air_date' => 'November 26, 2016'],
                    ['title' => 'Another One Bites the Dust, Part 2', 'air_date' => 'December 3, 2016'],
                    ['title' => 'Crazy D (Diamond) is Unbreakable, Part 1', 'air_date' => 'December 10, 2016'],
                    ['title' => 'Crazy D (Diamond) is Unbreakable, Part 2', 'air_date' => 'December 17, 2016'],
                    ['title' => 'Goodbye, Morioh - The Heart of Gold', 'air_date' => 'December 24, 2016'],
                ],
            ],
            [
                'number' => 5,
                'title' => 'Golden Wind',
                'release_year' => 2018,
                'summary' => 'Giorno Giovanna dreams of becoming a Gang-Star to clean up the Italian mafia.',
                'trailer_url' => 'https://www.youtube.com/watch?v=n2u21n2V0do',
                'episodes' => [
                    ['title' => 'Gold Experience', 'air_date' => 'October 6, 2018'],
                    ['title' => 'Bucciarati Is Coming', 'air_date' => 'October 13, 2018'],
                    ['title' => 'Meet the Gangster Behind the Wall', 'air_date' => 'October 20, 2018'],
                    ['title' => 'Joining the Gang', 'air_date' => 'October 27, 2018'],
                    ['title' => 'Find Polpo\'s Fortune!', 'air_date' => 'November 3, 2018'],
                    ['title' => 'Moody Blues\' Counterattack', 'air_date' => 'November 10, 2018'],
                    ['title' => 'Sex Pistols Appears, Part 1', 'air_date' => 'November 17, 2018'],
                    ['title' => 'Sex Pistols Appears, Part 2', 'air_date' => 'November 24, 2018'],
                    ['title' => 'The First Mission from the Boss', 'air_date' => 'December 1, 2018'],
                    ['title' => 'Hitman Team', 'air_date' => 'December 8, 2018'],
                    ['title' => 'Narancia\'s Aerosmith', 'air_date' => 'December 15, 2018'],
                    ['title' => 'The Second Mission from the Boss', 'air_date' => 'December 22, 2018'],
                    ['title' => 'Man in the Mirror and Purple Haze', 'air_date' => 'December 29, 2018'],
                    ['title' => 'Express Train to Florence', 'air_date' => 'January 12, 2019'],
                    ['title' => 'The Grateful Dead, Part 1', 'air_date' => 'January 19, 2019'],
                    ['title' => 'The Grateful Dead, Part 2', 'air_date' => 'January 26, 2019'],
                    ['title' => 'Baby Face', 'air_date' => 'February 2, 2019'],
                    ['title' => 'Head to Venice!', 'air_date' => 'February 9, 2019'],
                    ['title' => 'White Album', 'air_date' => 'February 16, 2019'],
                    ['title' => 'The Final Mission from the Boss', 'air_date' => 'February 23, 2019'],
                    ['title' => 'The Mystery of King Crimson', 'air_date' => 'March 2, 2019'],
                    ['title' => 'The \'G\' in Guts', 'air_date' => 'March 16, 2019'],
                    ['title' => 'Clash and Talking Head', 'air_date' => 'March 23, 2019'],
                    ['title' => 'Notorious B.I.G', 'air_date' => 'March 30, 2019'],
                    ['title' => 'Spice Girl', 'air_date' => 'April 6, 2019'],
                    ['title' => 'A Little Story From The Past ~My Name Is Doppio~', 'air_date' => 'April 13, 2019'],
                    ['title' => 'King Crimson vs. Metallica', 'air_date' => 'April 20, 2019'],
                    ['title' => 'Beneath a Sky on the Verge of Falling', 'air_date' => 'April 27, 2019'],
                    ['title' => 'Get to the Roman Colosseum!', 'air_date' => 'May 11, 2019'],
                    ['title' => 'Green Day and Oasis, Part 1', 'air_date' => 'May 18, 2019'],
                    ['title' => 'Green Day and Oasis, Part 2', 'air_date' => 'May 25, 2019'],
                    ['title' => 'Green Day and Oasis, Part 3', 'air_date' => 'June 1, 2019'],
                    ['title' => 'His Name Is Diavolo', 'air_date' => 'June 8, 2019'],
                    ['title' => 'The Requiem Quietly Plays, Part 1', 'air_date' => 'June 15, 2019'],
                    ['title' => 'The Requiem Quietly Plays, Part 2', 'air_date' => 'June 22, 2019'],
                    ['title' => 'Diavolo Surfaces', 'air_date' => 'June 29, 2019'],
                    ['title' => 'King of Kings', 'air_date' => 'July 6, 2019'],
                    ['title' => 'Gold Experience Requiem', 'air_date' => 'July 28, 2019'],
                    ['title' => 'The Sleeping Slave', 'air_date' => 'July 28, 2019'],
                ],
            ],
            [
                'number' => 6,
                'title' => 'Stone Ocean',
                'release_year' => 2021,
                'summary' => 'Jolyne Cujoh is framed for a crime and must escape Green Dolphin Street Prison.',
                'trailer_url' => 'https://www.youtube.com/watch?v=EeCX8Y0a278',
                'episodes' => [
                    ['title' => 'Stone Ocean', 'air_date' => 'December 1, 2021'],
                    ['title' => 'Stone Free', 'air_date' => 'December 1, 2021'],
                    ['title' => 'The Visitor (1)', 'air_date' => 'December 1, 2021'],
                    ['title' => 'The Visitor (2)', 'air_date' => 'December 1, 2021'],
                    ['title' => 'Prisoner of Love', 'air_date' => 'December 1, 2021'],
                    ['title' => 'Ermes\'s Stickers', 'air_date' => 'December 1, 2021'],
                    ['title' => 'There\'s Six of Us!', 'air_date' => 'December 1, 2021'],
                    ['title' => 'Foo Fighters', 'air_date' => 'December 1, 2021'],
                    ['title' => 'Debt Collector Marilyn Manson', 'air_date' => 'December 1, 2021'],
                    ['title' => 'Operation Savage Garden (Head to the Courtyard!) (1)', 'air_date' => 'December 1, 2021'],
                    ['title' => 'Operation Savage Garden (Head to the Courtyard!) (2)', 'air_date' => 'December 1, 2021'],
                    ['title' => 'Torrential Downpour Warning', 'air_date' => 'December 1, 2021'],
                    ['title' => 'Kiss of Love and Revenge (1)', 'air_date' => 'September 1, 2022'],
                    ['title' => 'Kiss of Love and Revenge (2)', 'air_date' => 'September 1, 2022'],
                    ['title' => 'Ultra Security House Unit', 'air_date' => 'September 1, 2022'],
                    ['title' => 'The Secret of Guard Westwood', 'air_date' => 'September 1, 2022'],
                    ['title' => 'Enter the Dragon\'s Dream', 'air_date' => 'September 1, 2022'],
                    ['title' => 'Enter the Foo Fighters', 'air_date' => 'September 1, 2022'],
                    ['title' => 'Birth of the \'Green\'', 'air_date' => 'September 1, 2022'],
                    ['title' => 'F.F. – The Witness', 'air_date' => 'September 1, 2022'],
                    ['title' => 'Awaken', 'air_date' => 'September 1, 2022'],
                    ['title' => 'Time for Heaven! New Moon! New Priest!', 'air_date' => 'September 1, 2022'],
                    ['title' => 'Jail House Lock!', 'air_date' => 'September 1, 2022'],
                    ['title' => 'Jailbreak...', 'air_date' => 'September 1, 2022'],
                    ['title' => 'Bohemian Rhapsody (1)', 'air_date' => 'December 1, 2022'],
                    ['title' => 'Bohemian Rhapsody (2)', 'air_date' => 'December 1, 2022'],
                    ['title' => 'Sky High', 'air_date' => 'December 1, 2022'],
                    ['title' => 'Heaven Is at Hand: Three Days Until the New Moon', 'air_date' => 'December 1, 2022'],
                    ['title' => 'Under World', 'air_date' => 'December 1, 2022'],
                    ['title' => 'Heavy Weather (1)', 'air_date' => 'December 1, 2022'],
                    ['title' => 'Heavy Weather (2)', 'air_date' => 'December 1, 2022'],
                    ['title' => 'Heavy Weather (3)', 'air_date' => 'December 1, 2022'],
                    ['title' => 'Gravity of the New Moon', 'air_date' => 'December 1, 2022'],
                    ['title' => 'C-Moon (1)', 'air_date' => 'December 1, 2022'],
                    ['title' => 'C-Moon (2)', 'air_date' => 'December 1, 2022'],
                    ['title' => 'Made in Heaven (1)', 'air_date' => 'December 1, 2022'],
                    ['title' => 'Made in Heaven (2)', 'air_date' => 'December 1, 2022'],
                    ['title' => 'What a Wonderful World', 'air_date' => 'December 1, 2022'],
                ],
            ],
        ];

        // Load all summaries from JSON files
        $summaries = [];
        $summaryFiles = [
            'jojo_summaries.json',
            'jojo_summaries_batch2.json',
            'jojo_summaries_batch3.json',
            'jojo_summaries_batch4.json',
        ];

        foreach ($summaryFiles as $file) {
            $path = database_path('data/'.$file);
            if (file_exists($path)) {
                $data = json_decode(file_get_contents($path), true);
                if ($data) {
                    foreach ($data as $key => $value) {
                        // Handle both formats (number/summary or key/value)
                        $absNum = is_array($value) && isset($value['number']) ? (int) $value['number'] : (int) $key;
                        $summaryText = is_array($value) && isset($value['summary']) ? $value['summary'] : $value;
                        $summaries[$absNum] = $summaryText;
                    }
                }
            }
        }

        // Load thumbnails from scraped data
        $thumbnails = [];
        $thumbnailPath = database_path('data/scraped_thumbnails_full.json');
        if (file_exists($thumbnailPath)) {
            $thumbnails = json_decode(file_get_contents($thumbnailPath), true);
        }

        $absoluteCounter = 1;
        foreach ($all_data as $pData) {
            $episodes = $pData['episodes'];
            unset($pData['episodes']);

            $part = Part::create($pData);

            // Associate poster image
            $part->media()->create(['path' => 'posters/part'.$part->number.'.png']);

            foreach ($episodes as $index => $eData) {
                $summary = $summaries[$absoluteCounter] ?? 'A fated encounter in the history of the Joestars.';

                Episode::create([
                    'part_id' => $part->id,
                    'title' => $eData['title'],
                    'episode_number' => $index + 1,
                    'release_date' => Carbon::parse($eData['air_date'])->format('Y-m-d'),
                    'imdb_score' => rand(75, 98) / 10,
                    'summary' => $summary,
                    'thumbnail_url' => $thumbnails[$absoluteCounter] ?? null,
                ]);

                $absoluteCounter++;
            }
        }
    }
}
