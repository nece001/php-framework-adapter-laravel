<?php

namespace Nece\Framework\Adapter;

use Illuminate\Console\Command as LaravelCommand;
use Nece\Framework\Adapter\Contract\Command as ContractCommand;

abstract class Command extends LaravelCommand implements ContractCommand
{
    /**
     * 默认命令名称
     *
     * @var string
     */
    protected static $defaultName = '';

    /**
     * 默认命令描述
     *
     * @var string
     */
    protected static $defaultDescription = '';

    /**
     * 参数定义列表
     *
     * @var array
     */
    private $argDefinitions = [];

    /**
     * 选项定义列表
     *
     * @var array
     */
    private $optDefinitions = [];

    /**
     * 构造函数
     */
    public function __construct()
    {
        // 先调用 configure() 让子类注册参数和选项
        $this->configure();

        // 生成 signature（包含命令名称）
        $signature = $this->generateSignature();

        // 设置生成的 signature（在 configure() 之后）
        if (!empty($signature)) {
            $this->signature = $signature;
        }

        // 设置描述
        if (!empty(static::$defaultDescription)) {
            $this->description = static::$defaultDescription;
        }

        // 调用父构造函数（不传名称，使用 signature 中的名称）
        parent::__construct();
    }

    /**
     * 执行配置（在子类中重写）
     *
     * @return void
     */
    protected function configure(): void {}

    /**
     * 生成命令签名
     *
     * @return string
     */
    protected function generateSignature(): string
    {
        $parts = [];

        // 添加命令名称
        if (!empty(static::$defaultName)) {
            $parts[] = static::$defaultName;
        }

        // 添加参数
        foreach ($this->argDefinitions as $arg) {
            $part = '{' . $arg['name'];

            // 处理模式
            if ($arg['mode'] & self::ARGUMENT_IS_ARRAY) {
                $part .= '*';
            } elseif (!($arg['mode'] & self::ARGUMENT_REQUIRED)) {
                $part .= '?';
            }

            // 处理默认值
            if ($arg['default'] !== null) {
                $part .= '=' . $this->formatDefaultValue($arg['default']);
            }

            $part .= '}';
            $parts[] = $part;
        }

        // 添加选项
        foreach ($this->optDefinitions as $opt) {
            $part = '{--';

            // 处理快捷方式
            if (!empty($opt['shortcut'])) {
                $part .= $opt['shortcut'] . '|';
            }

            $part .= $opt['name'];

            // 处理模式
            if ($opt['mode'] & self::OPTION_VALUE_IS_ARRAY) {
                $part .= '=*';
            } elseif ($opt['mode'] & self::OPTION_VALUE_REQUIRED) {
                $part .= '=';
            } elseif ($opt['mode'] & self::OPTION_VALUE_OPTIONAL) {
                $part .= '=?';
            }

            // 处理默认值
            if ($opt['default'] !== null && ($opt['mode'] & (self::OPTION_VALUE_REQUIRED | self::OPTION_VALUE_OPTIONAL | self::OPTION_VALUE_IS_ARRAY))) {
                $part .= $this->formatDefaultValue($opt['default']);
            }

            $part .= '}';
            $parts[] = $part;
        }

        return implode(' ', $parts);
    }

    /**
     * 格式化默认值
     *
     * @param mixed $value
     * @return string
     */
    protected function formatDefaultValue($value): string
    {
        if (is_string($value)) {
            return '"' . str_replace('"', '\\"', $value) . '"';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_array($value)) {
            return '[' . implode(',', array_map([$this, 'formatDefaultValue'], $value)) . ']';
        }
        return (string)$value;
    }

    /**
     * 获取命令行参数
     *
     * @param string $name
     * @return mixed
     */
    public function getArg($name)
    {
        return $this->argument($name);
    }

    /**
     * 获取命令行选项
     *
     * @param string $name
     * @return mixed
     */
    public function getOpt($name)
    {
        return $this->option($name);
    }

    /**
     * 询问用户
     *
     * @param string $question
     * @param mixed $default
     * @return mixed
     */
    public function showAsk($question, $default = null)
    {
        return $this->ask($question, $default);
    }

    /**
     * 确认用户操作
     *
     * @param string $question
     * @param bool $default
     * @return bool
     */
    public function showConfirm($question, $default = false)
    {
        return $this->confirm($question, $default);
    }

    /**
     * 选择用户操作
     *
     * @param string $question
     * @param array $choices
     * @param mixed $default
     * @param int|null $attempts
     * @param bool $multiple
     * @return mixed
     */
    public function showChoice($question, array $choices, $default = null, $attempts = null, $multiple = false)
    {
        return $this->choice($question, $choices, $default, $attempts, $multiple);
    }

    /**
     * 输出空行
     *
     * @param int $count
     * @return void
     */
    public function showLine($count = 1)
    {
        $this->newLine($count);
    }

    /**
     * 输出信息消息
     *
     * @param string $message
     * @return void
     */
    public function showInfo($message)
    {
        $this->info($message);
    }

    /**
     * 输出注释消息
     *
     * @param string $message
     * @return void
     */
    public function showComment($message)
    {
        $this->comment($message);
    }

    /**
     * 输出问题消息
     *
     * @param string $question
     * @return void
     */
    public function showQuestion($question)
    {
        $this->question($question);
    }

    /**
     * 输出警告消息
     *
     * @param string $message
     * @return void
     */
    public function showWarn($message)
    {
        $this->warn($message);
    }

    /**
     * 输出错误消息
     *
     * @param string $message
     * @return void
     */
    public function showError($message)
    {
        $this->error($message);
    }

    /**
     * 添加命令行参数
     *
     * @param string $name 参数名称
     * @param int|null $mode 参数模式
     * @param string $description 参数描述
     * @param mixed $default 默认值
     * @param array $suggestedValues 输入补全的值
     * @return $this
     */
    public function addArg(string $name, ?int $mode = null, string $description = '', $default = null, array $suggestedValues = []): static
    {
        $this->argDefinitions[] = [
            'name' => $name,
            'mode' => $mode ?? self::ARGUMENT_OPTIONAL,
            'description' => $description,
            'default' => $default,
            'suggestedValues' => $suggestedValues,
        ];
        return $this;
    }

    /**
     * 添加命令行选项
     *
     * @param string $name 选项名称
     * @param string|null $shortcut 快捷方式
     * @param int|null $mode 选项模式
     * @param string $description 选项描述
     * @param mixed $default 默认值
     * @param array $suggestedValues 输入补全的值
     * @return $this
     */
    public function addOpt(string $name, ?string $shortcut = null, ?int $mode = null, string $description = '', $default = null, array $suggestedValues = []): static
    {
        $this->optDefinitions[] = [
            'name' => $name,
            'shortcut' => $shortcut,
            'mode' => $mode ?? self::OPTION_VALUE_NONE,
            'description' => $description,
            'default' => $default,
            'suggestedValues' => $suggestedValues,
        ];
        return $this;
    }
}
