<?php

namespace Prantho\Crud\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class CrudCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name} {--option=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD. Example: php artisan crud:make {name}=Model Name';

    /**
     * Execute the console command.
     *
     * @return int
     */
    protected $name;


    public function handle()
    {
        $this->name = $this->argument('name');
        $stub = $this->getStub();
        $stub = $this->generateCrudCode($stub);
        $this->makeController($stub);
        if ($this->option('option') != 'api') {
            $this->makeView();
        }
        $this->info('Created CRUD...');
    }
    protected function generateCrudCode($stub)
    {
        $cruds = ["edit", "index", "show", "store", "update", "destroy"];
        $methods = [];
        $places = [];
        foreach ($cruds as $key => $crud) {
            $data = $this->{$crud}();
            $places[] = "{{ $crud }}";
            $methods[$key] = "$data";
        }
        $rootNamespace = $this->rootNamespace();
        $className = $this->name . "Controller";
        return $stub = str_replace(array_merge($places, ["{{ rootNamespace }}", "{{ class }}"]), array_merge($methods, [$rootNamespace, $className]), $stub);
    }
    protected function getStub()
    {
        if ($this->option('option') == 'api') {

            return File::get(__DIR__ . "/../stubs/controller.api.stub");
        }
        return File::get(__DIR__ . "/../stubs/controller.view.stub");
    }
    protected function makeController($stub)
    {
        $path = app_path('Http/Controllers/' . $this->name . 'Controller.php');

        File::put($path, $stub);
    }
    protected function makeView()
    {
        $views = ["edit", "index", "show"];
        File::makeDirectory(resource_path('views/' . $this->name), 0777, true, true);
        foreach ($views as $view) {
            $path = resource_path('views/' . strtoloawer("$this->name") . '/' . $view . '.blade.php');
            File::put($path, "");
        }
    }
    protected function getColumns()
    {
        $model = "App\Models\\$this->name";
        if (class_exists($model)) {
            $model = new $model;
            return Schema::getColumnListing($model->getTable());
        }
        return $this->error('Model not found');
    }
    protected function store()
    {
        $validate = "";
        foreach ($this->getColumns() as $key => $value) {
            $validate .= "'$value' => 'required',\n";
        }
        $store = "\Illuminate\Support\Facades\Cache::forget('$this->name');\n";
        $store .= "\$validData=\$request->validate([" . $validate . "]);\n";
        $store .= "\App\Models\\$this->name::create(\$validData);\n";
        return $store;
    }
    protected function update()
    {
        $validate = "";
        foreach ($this->getColumns() as $key => $value) {
            $validate .= "'$value' => 'sometimes',\n";
        }
        $update = "\Illuminate\Support\Facades\Cache::forget('$this->name');\n";
        $update .= "\$validData=\$request->validate([" . $validate . "]);\n";
        $update .= "\App\Models\\$this->name::where('id',\$id)->update(\$validData);\n";

        return $update;
    }
    protected function index()
    {
        $index = "\$index=\Illuminate\Support\Facades\Cache::remember('$this->name', 84000, function () {\n";
        $index .= "return \App\Models\\$this->name::all();\n";
        $index .= "});";
        return $index;
    }
    protected function show()
    {
        $show = "\$show=\App\Models\\$this->name::findOrFail(\$id);\n";
        return $show;
    }
    protected function edit()
    {
        $edit = "\$edit=\App\Models\\$this->name::find(\$id);\n";
        return $edit;
    }
    protected function destroy()
    {
        $delete = "\Illuminate\Support\Facades\Cache::forget('$this->name');\n";
        $delete .= "\App\Models\\$this->name::findOrFail(\$id)->delete();\n";
        return $delete;
    }
}
