<?php
    /**
     * 返回可读性更好的文件尺寸
     */
    function human_filesize($bytes,$decimals = 2)
    {
        $size = ['B','KB','MB','GB','TB','PB'];
        $factor = floor((strlen($bytes)-1)/3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .@$size[$factor];

    }

    function is_image($mineType)
    {
        return starts_with($mineType,'image/');
    }
