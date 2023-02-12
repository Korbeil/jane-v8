<?php

return [
    '$comment' => 'https://github.com/zyedidia/micro',
    '$schema' => 'http://json-schema.org/draft-07/schema#',
    'additionalProperties' => false,
    'properties' => [
        'autoindent' => [
            'description' => 'Whether to use the same indentation as a previous line
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'autosave' => [
            'description' => 'A delay between automatic saves
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'integer',
            'minimum' => 0,
            'default' => 0,
        ],
        'autosu' => [
            'description' => 'Whether attempt to use super user privileges
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'backup' => [
            'description' => 'Whether to backup all open buffers
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'backupdir' => [
            'description' => 'A directory to store backups
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'default' => '',
        ],
        'basename' => [
            'description' => 'Whether to show a basename instead of a full path
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'clipboard' => [
            'description' => 'A way to access the system clipboard
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'enum' => [
                'external',
                'terminal',
                'internal',
            ],
            'default' => 'external',
        ],
        'colorcolumn' => [
            'description' => 'A position to display a column
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'integer',
            'minimum' => 0,
            'default' => 0,
        ],
        'colorscheme' => [
            'description' => 'A color scheme
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'enum' => [
                'atom-dark',
                'bubblegum',
                'cmc-16',
                'cmc-tc',
                'darcula',
                'default',
                'dracula-tc',
                'dukedark-tc',
                'dukelight-tc',
                'dukeubuntu-tc',
                'geany',
                'gotham',
                'gruvbox',
                'gruvbox-tc',
                'material-tc',
                'monokai-dark',
                'monokai',
                'one-dark',
                'railscast',
                'simple',
                'solarized',
                'solarized-tc',
                'sunny-day',
                'twilight',
                'zenburn',
            ],
            'default' => 'default',
        ],
        'cursorline' => [
            'description' => 'Whether to highlight a line with a cursor with a different color
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'diffgutter' => [
            'description' => 'Whether to display diff inticators before lines
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'divchars' => [
            'description' => 'Divider chars for vertical and horizontal splits
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'default' => '|-',
        ],
        'divreverse' => [
            'description' => 'Whether to use inversed color scheme colors for splits
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'encoding' => [
            'description' => 'An encoding used to open and save files
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'default' => 'utf-8',
        ],
        'eofnewline' => [
            'description' => 'Whether to add a missing trailing new line
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'fastdirty' => [
            'description' => 'Whether to use a fast algorithm to determine whether a file is changed
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'fileformat' => [
            'description' => 'A line ending format
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'enum' => [
                'unix',
                'dos',
            ],
            'default' => 'unix',
        ],
        'filetype' => [
            'description' => 'A filetype for the current buffer
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'default' => 'unknown',
        ],
        'hlsearch' => [
            'description' => 'Whether to highlight all instances of a searched text after a successful search
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'incsearch' => [
            'description' => 'Whether to enable an incremental search in `Find` prompt
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'ignorecase' => [
            'description' => 'Whether to perform case-insensitive searches
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'indentchar' => [
            'description' => 'An indentation character
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'maxLength' => 1,
            'default' => ' ',
        ],
        'infobar' => [
            'description' => 'Whether to enable a line at the bottom where messages are printed
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'keepautoindent' => [
            'description' => 'Whether add a whitespace while using autoindent
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'keymenu' => [
            'description' => 'Whether to display nano-style key menu at the bottom
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'matchbrace' => [
            'description' => 'Whether to underline matching braces
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'mkparents' => [
            'description' => 'Whether to create missing directories
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'mouse' => [
            'description' => 'Whether to enable mouse support
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'paste' => [
            'description' => 'Whether to treat characters sent from the terminal in a single chunk as a paste event
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'parsecursor' => [
            'description' => 'Whether to extract a line number and a column to open files with from file names
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'permbackup' => [
            'description' => 'Whether to permanently save backups
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'pluginchannels' => [
            'description' => 'A file with list of plugin channels
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'default' => 'https://raw.githubusercontent.com/micro-editor/plugin-channel/master/channel.json',
        ],
        'pluginrepos' => [
            'description' => 'Plugin repositories
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'array',
            'uniqueItems' => true,
            'items' => [
                'description' => 'A pluging repository
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
                'type' => 'string',
            ],
            'default' => [
            ],
        ],
        'readonly' => [
            'description' => 'Whether to forbid buffer editing
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'rmtrailingws' => [
            'description' => 'Whether to remove trailing whitespaces
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'ruler' => [
            'description' => 'Whether to display line numbers
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'relativeruler' => [
            'description' => 'Whether to display relative line numbers
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'savecursor' => [
            'description' => 'Whether to save cursor position in files
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'savehistory' => [
            'description' => 'Whether to save command history between closing and re-opening editor
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'saveundo' => [
            'description' => 'Whether to save undo after closing file
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'scrollbar' => [
            'description' => 'Whether to save undo after closing file
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'scrollmargin' => [
            'description' => 'A margin at which a view starts scrolling when a cursor approaches an edge of a view
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'integer',
            'default' => 3,
        ],
        'scrollspeed' => [
            'description' => 'Line count to scroll for one scroll event
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'integer',
            'default' => 2,
        ],
        'smartpaste' => [
            'description' => 'Whether to add a leading whitespace while pasting multiple lines
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'softwrap' => [
            'description' => 'Whether to wrap long lines
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'splitbottom' => [
            'description' => 'Whether to create a new horizontal split below the current one
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'splitright' => [
            'description' => 'Whether to create a new vertical split right of the current one
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'statusformatl' => [
            'description' => 'Format string of left-justified part of the statusline
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'default' => '$(filename) $(modified)($(line),$(col)) $(status.paste)| ft:$(opt:filetype) | $(opt:fileformat) | $(opt:encoding)',
        ],
        'statusformatr' => [
            'description' => 'Format string of right-justified part of the statusline
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'default' => '$(bind:ToggleKeyMenu): bindings, $(bind:ToggleHelp): help',
        ],
        'statusline' => [
            'description' => 'Whether to display a status line
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'default' => 'sudo',
        ],
        'sucmd' => [
            'description' => 'A super user command
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'string',
            'default' => 'sudo',
            'examples' => [
                'sudo',
                'doas',
            ],
        ],
        'syntax' => [
            'description' => 'Whether to enable a syntax highlighting
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'tabmovement' => [
            'description' => 'Whether to navigate spaces at the beginning of lines as if they are tabs
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'tabhighlight' => [
            'description' => 'Whether to invert tab character colors
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'tabreverse' => [
            'description' => 'Whether to reverse tab bar colors
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'tabsize' => [
            'description' => 'A tab size
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'integer',
            'default' => 4,
        ],
        'tabstospaces' => [
            'description' => 'Whether to use spaces instead of tabs
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'useprimary' => [
            'description' => 'Whether to use primary clipboard to copy selections in the background
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => true,
        ],
        'wordwrap' => [
            'description' => 'Whether to wrap long lines by words
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
        'xterm' => [
            'description' => 'Whether to assume that the current terminal is `xterm`
https://github.com/zyedidia/micro/blob/master/runtime/help/options.md#options',
            'type' => 'boolean',
            'default' => false,
        ],
    ],
    'title' => 'A micro editor config schema',
    'type' => 'object',
];
