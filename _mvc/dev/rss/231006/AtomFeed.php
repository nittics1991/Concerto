<?php

/**
*   ATOM FREED
*
*   @version 230927
*/

declare(strict_types=1);

namespace Concerto;


class AtomFeed
{
    /**
    *   @var string[]
    */
    private array $element_names = [
        'Author', 'Category', 'Contributor',
        'Generator', 'Icon', 'Id',
        'Link', 'Logo', 'Rights',
        'Subtitle', 'Title', 'Updated',
        'Entry',
    ];

    /**
    *   @var mixed[]
    */
    private array $top_elements = [
        'author' => [
            'level' => RequirementLevel::MUST,
            'condition' => RequirementCount::ONE_OR_MORE,
            'attributes' => [],
            'children' => ['name', 'uri', 'email'],
        ],
        'category' => [
            'level' => RequirementLevel::MAY,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
        'contributor' => [
            'level' => RequirementLevel::MAY,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
        'generator' => [
            'level' => RequirementLevel::MUST_NOT,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
        'icon' => [
            'level' => RequirementLevel::MUST_NOT,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
        'id' => [
            'level' => RequirementLevel::MUST,
            'condition' => RequirementCount::ONLY,
            'attributes' => [],
            'children' => [],
        ],
        'link' => [
            'level' => RequirementLevel::MAY,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
        'logo' => [
            'level' => RequirementLevel::MUST_NOT,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
        'rights' => [
            'level' => RequirementLevel::MUST_NOT,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
        'subtitle' => [
            'level' => RequirementLevel::MUST_NOT,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
        'title' => [
            'level' => RequirementLevel::MUST,
            'condition' => RequirementCount::ONLY,
            'attributes' => [],
            'children' => [],
        ],
        'updated' => [
            'level' => RequirementLevel::MUST,
            'condition' => RequirementCount::ONLY,
            'attributes' => [],
            'children' => [],
        ],
        'entry' => [
            'level' => RequirementLevel::MAY,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
    ];

    /**
    *   @var mixed[]
    */
    private array $sub_elements = [
        'name' => [
            'level' => RequirementLevel::MUST,
            'condition' => RequirementCount::ONE,
            'attributes' => [],
            'children' => [],
        ],
        'uri' => [
            'level' => RequirementLevel::MUST_NOT,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
        'email' => [
            'level' => RequirementLevel::MUST_NOT,
            'condition' => RequirementCount::ANY,
            'attributes' => [],
            'children' => [],
        ],
    ];














    /**
    *   @var string[]
    */
    private array $contents = [];

    /**
    *   {inherit}
    */
    public function __call(
        string $name,
        array $arguments
    ): mixed {
        if (!in_array($name, $this->element_names)) {
            throw new BadMethodCallException(
                "not defined method:{$name}",
            );
        }
        
        $this->contents[$name] = $arguments;
        
        return static;
    }

    /**
    *   generate
    *
    *   @param string $param
    *   @return void
    */
    public function generate():string
    {
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
    *   validAuthor
    *
    *   @param string $param
    *   @return void
    */
    private function validAuthor():bool
    {
        if (
            !isset($this->author) ||
            ! is_array($this->author)
        ) {
            return false;
        }
        
        $keys = array_keys($this->author);
        
        if (!in_array('name', $keys)) {
            return false;
        }
        
        return empty(array_diff(
            $keys,
            ['name', 'uri', 'email'])
        ));
    }
    
    
    
}
