<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class holidayMstUp extends Command
{
    /**
     * The name and signature of the console command.
     * 引数なしの場合でもエアラーハンドリングできるように引数なしも許可する 
     *
     * @var string
     */
    protected $signature = 'batch:holidayMstUpe {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 引数(yyyy)チェック
        // なしの場合はエラー
        if ($this->argument('year')) {
            $targetYear = $this->argument('year');
            var_dump($targetYear);
        } else {
            var_dump($this->argument('year'));
            var_dump('引数に年を設定してください。');
        }
        

        // apiを使用し、祝日を取得
        // 取得した祝日をマスタに登録
    }

    public function validate() {
        $definition = $this->definition;
        $givenArguments = $this->arguments;

        $missingArguments = array_filter(array_keys($definition->getArguments()), function ($argument) use ($definition, $givenArguments) {
            return !\array_key_exists($argument, $givenArguments) && $definition->getArgument($argument)->isRequired();
        });

        if (\count($missingArguments) > 0) {
            var_dump('日k数を設定してください');
        }
    }
}
