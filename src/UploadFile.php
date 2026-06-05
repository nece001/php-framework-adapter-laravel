<?php

namespace Nece\Framework\Adapter;

use Nece\Framework\Adapter\Contract\UploadFile as ContractUploadFile;
use Illuminate\Http\UploadedFile;

class UploadFile implements ContractUploadFile
{
    /**
     * 上传文件
     *
     * @var UploadedFile
     */
    private $upload_file;

    /**
     * 创建上传文件实例
     *
     * @author nece001@163.com
     * @create 2026-06-04 16:54:01
     *
     * @param File $file
     * @return static
     */
    public static function instance($file): static
    {
        return new static($file);
    }

    /**
     * @inheritDoc
     */
    public static function instances(array $files): array
    {
        $instances = [];
        foreach ($files as $field => $file) {
            $instances[$field] = new static($file);
        }
        return $instances;
    }

    public function __construct(UploadedFile $file)
    {
        $this->upload_file = $file;
    }
}
