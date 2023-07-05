<?php

namespace DMarti\ExamplesSymfony5\Constant;

final class DbForeignKeyAction
{
    /** @var string */
    public const RESTRICT = 'RESTRICT';
    /** @var string */
    public const NO_ACTION = 'NO ACTION';
    /** @var string */
    public const CASCADE = 'CASCADE';
    /** @var string */
    public const SET_NULL = 'SET NULL';
    /** @var string */
    public const SET_DEFAULT = 'SET DEFAULT';
}
