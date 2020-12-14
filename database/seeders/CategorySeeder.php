<?php

namespace Database\Seeders;

use App\Services\CategoryService;
use Illuminate\Database\Seeder;
use Illuminate\Validation\ValidationException;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws ValidationException
     */
    public function run()
    {
        $root1 = (new CategoryService())->create(['name' => 'Computers & Tablets']);
        $root2 = (new CategoryService())->create(['name' => 'Video & Multimedia']);
        $root3 = (new CategoryService())->create(['name' => 'Home Security']);
        $root4 = (new CategoryService())->create(['name' => 'Consumer Electronics']);

        (new CategoryService())->create(['name' => 'WorkStations'])->setParentCategory($root1->getModel());
        (new CategoryService())->create(['name' => 'Gaming PCs'])->setParentCategory($root1->getModel());
        (new CategoryService())->create(['name' => 'Laptops'])->setParentCategory($root1->getModel());

        $root2_lvl1 = (new CategoryService())->create(['name' => 'Cameras'])->setParentCategory($root2->getModel());
        (new CategoryService())->create(['name' => 'DSLR Cameras'])->setParentCategory($root2_lvl1->getModel());
        (new CategoryService())->create(['name' => 'Mirror less Cameras'])->setParentCategory($root2_lvl1->getModel());

        (new CategoryService())->create(['name' => 'Speakers and mics'])->setParentCategory($root2->getModel());

        (new CategoryService())->create(['name' => 'Outdoor Surveillance'])->setParentCategory($root3->getModel());

    }
}
