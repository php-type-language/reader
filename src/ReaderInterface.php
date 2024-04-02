<?php

declare(strict_types=1);

namespace TypeLang\Reader;

interface ReaderInterface extends
    ConstantReaderInterface,
    PropertyReaderInterface,
    FunctionReaderInterface,
    ParameterReaderInterface {}
