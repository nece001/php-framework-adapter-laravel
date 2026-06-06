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
     * @param UploadedFile $file
     * @return static
     */
    public static function instance($file): static
    {
        return new static($file);
    }

    /**
     * 创建上传文件实例数组
     *
     * @param array $files
     * @return array
     */
    public static function instances(array $files): array
    {
        $instances = [];
        foreach ($files as $field => $file) {
            $instances[$field] = new static($file);
        }
        return $instances;
    }

    /**
     * 构造函数
     *
     * @param UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        $this->upload_file = $file;
    }

    /**
     * 获取上传文件名
     *
     * @return string|null
     */
    public function getUploadName(): ?string
    {
        return $this->upload_file->getClientOriginalName();
    }

    /**
     * 获取上传文件的MIME类型
     *
     * @return string|null
     */
    public function getUploadMimeType(): ?string
    {
        return $this->upload_file->getClientMimeType();
    }

    /**
     * 获取上传文件的扩展名
     *
     * @return string
     */
    public function getUploadExtension(): string
    {
        return $this->upload_file->getClientOriginalExtension();
    }

    /**
     * 获取上传错误码
     *
     * @return int|null
     */
    public function getUploadErrorCode(): ?int
    {
        return $this->upload_file->getError();
    }

    /**
     * 检查上传是否有效
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->upload_file->isValid();
    }

    /**
     * 移动文件到指定位置
     *
     * @param string $destination
     * @return \SplFileInfo
     */
    public function move(string $destination): \SplFileInfo
    {
        $info = pathinfo($destination);
        $path = $info['dirname'];
        $filename = $info['basename'];

        return $this->upload_file->move($path, $filename);
    }

    /**
     * 获取文件路径（不含文件名）
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->upload_file->getPath();
    }

    /**
     * 获取文件名
     *
     * @return string
     */
    public function getFilename(): string
    {
        return $this->upload_file->getFilename();
    }

    /**
     * 获取完整路径
     *
     * @return string
     */
    public function getPathname(): string
    {
        return $this->upload_file->getPathname();
    }

    /**
     * 获取文件扩展名
     *
     * @return string
     */
    public function getExtension(): string
    {
        return $this->upload_file->getExtension();
    }

    /**
     * 获取文件基本名
     *
     * @param string|null $suffix
     * @return string
     */
    public function getBasename(?string $suffix = null): string
    {
        return $this->upload_file->getBasename($suffix);
    }

    /**
     * 是否为文件
     *
     * @return bool
     */
    public function isFile(): bool
    {
        return $this->upload_file->isFile();
    }

    /**
     * 是否为目录
     *
     * @return bool
     */
    public function isDir(): bool
    {
        return $this->upload_file->isDir();
    }

    /**
     * 是否为符号链接
     *
     * @return bool
     */
    public function isLink(): bool
    {
        return $this->upload_file->isLink();
    }

    /**
     * 获取文件大小（字节）
     *
     * @return int|false
     */
    public function getSize(): int|false
    {
        return $this->upload_file->getSize();
    }

    /**
     * 获取文件所有者
     *
     * @return int|false
     */
    public function getOwner(): int|false
    {
        return $this->upload_file->getOwner();
    }

    /**
     * 获取文件所属组
     *
     * @return int|false
     */
    public function getGroup(): int|false
    {
        return $this->upload_file->getGroup();
    }

    /**
     * 获取最后访问时间
     *
     * @return int|false
     */
    public function getATime(): int|false
    {
        return $this->upload_file->getATime();
    }

    /**
     * 获取最后修改时间
     *
     * @return int|false
     */
    public function getMTime(): int|false
    {
        return $this->upload_file->getMTime();
    }

    /**
     * 获取创建时间
     *
     * @return int|false
     */
    public function getCTime(): int|false
    {
        return $this->upload_file->getCTime();
    }

    /**
     * 获取文件权限
     *
     * @return int|false
     */
    public function getPerms(): int|false
    {
        return $this->upload_file->getPerms();
    }

    /**
     * 是否可读
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->upload_file->isReadable();
    }

    /**
     * 是否可写
     *
     * @return bool
     */
    public function isWritable(): bool
    {
        return $this->upload_file->isWritable();
    }

    /**
     * 是否可执行
     *
     * @return bool
     */
    public function isExecutable(): bool
    {
        return $this->upload_file->isExecutable();
    }

    /**
     * 获取真实路径
     *
     * @return string|false
     */
    public function getRealPath(): string|false
    {
        return $this->upload_file->getRealPath();
    }
}