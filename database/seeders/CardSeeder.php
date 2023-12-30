<?php

namespace Database\Seeders;

use App\Models\CardGame;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    private $mainCards = ["02_Trefl", "03_Trefl", "04_Trefl", "05_Trefl", "06_Trefl", "07_Trefl", "08_Trefl", "09_Trefl", "10_Trefl", "0J_Trefl", "0Q_Trefl", "0K_Trefl", "0A_Trefl", "02_Pik", "03_Pik", "04_Pik", "05_Pik", "06_Pik", "07_Pik", "08_Pik", "09_Pik", "10_Pik", "0J_Pik", "0Q_Pik", "0K_Pik", "0A_Pik", "02_Kier", "03_Kier", "04_Kier", "05_Kier", "06_Kier", "07_Kier", "08_Kier", "09_Kier", "10_Kier", "0J_Kier", "0Q_Kier", "0K_Kier", "0A_Kier", "02_Karo", "03_Karo", "04_Karo", "05_Karo", "06_Karo", "07_Karo", "08_Karo", "09_Karo", "10_Karo", "0J_Karo", "0Q_Karo", "0K_Karo", "0A_Karo"];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->mainCards as $cardName) {
            CardGame::insert(['card' => $cardName]);
        }
    }
}
