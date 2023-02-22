<?php

namespace App\Enums;

enum NoticeState: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}
