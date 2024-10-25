<?php

/**
*   ExcelContents
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel\parts;

use RuntimeException;
use Concerto\excel\{
    ExcelArchive,
    ExcelEventDispatcher,
};
use Concerto\excel\parts\{
    ContentTypes,
    SharedStrings,
    SheetParts,
    WorkBookParts,
    WorkBookRels,
};

class ExcelContents
{
    /**
    *   @var ExcelArchive
    */
    protected ExcelArchive $archive;

    /**
    *   @var ExcelEventDispatcher
    */
    protected ExcelEventDispatcher $eventDispatcher;

    /**
    *   @var WorkBookParts
    */
    protected WorkBookParts $workBookParts;

    /**
    *   @var WorkBookRels
    */
    protected WorkBookRels $workBookRels;

    /**
    *   @var SharedStrings
    */
    protected SharedStrings $sharedStrings;

    /**
    *   @var ContentTypes
    */
    protected ContentTypes $contentTypes;

    /**
    *   __construct
    *
    *   @param ExcelArchive $archive
    */
    public function __construct(
        ExcelArchive $archive,
    ) {
        $this->archive = $archive;

        $this->eventDispatcher = new ExcelEventDispatcher();
    }

    /**
    *   getContentTypes
    *
    *   @return ContentTypes
    */
    public function getContentTypes(): ContentTypes
    {
        $this->contentTypes = $this->contentTypes ??
            new ContentTypes(
                $this->archive,
            );

        return $this->contentTypes;
    }

    /**
    *   getSharedStrings
    *
    *   @return SharedStrings
    */
    public function getSharedStrings(): SharedStrings
    {
        $this->sharedStrings = $this->sharedStrings ??
            new SharedStrings(
                $this->archive,
            );

        $this->getContentTypes();

        $contentTypes = $this->contentTypes;

        $this->eventDispatcher->addListener(
            $this->eventDispatcher->buildEventName(
                $this->sharedStrings::class,
                'addData',
                'create',
            ),
            function (SharedStrings $event) use ($contentTypes) {
                call_user_func(
                    [$contentTypes, 'addPartName'],
                    '/' . $event->getFilePath(),
                    $event::class,
                );
            },
        );

        $workBookRels = $this->getWorkBookRels();

        $this->eventDispatcher->addListener(
            $this->eventDispatcher->buildEventName(
                $this->sharedStrings::class,
                'addData',
                'create',
            ),
            function (SharedStrings $event) use ($workBookRels) {
                call_user_func(
                    [$workBookRels, 'addRelationship'],
                    basename($event->getFilePath()),
                    $event::class,
                );
            },
        );

        return $this->sharedStrings;
    }

    /**
    *   getSheetParts
    *
    *   @param string $sheet_name
    *   @return SheetParts
    */
    public function getSheetParts(
        string $sheet_name,
    ): SheetParts {
        $sheet_id = $this->findSheetPartsId(
            $sheet_name,
        );

        $sheet_file_name = $this->findSheetFileName(
            $sheet_id,
        );

        return new SheetParts(
            $this->archive,
            $sheet_file_name,
        );
    }

    /**
    *   getWorkBookParts
    *
    *   @return WorkBookParts
    */
    public function getWorkBookParts(): WorkBookParts
    {
        $this->workBookParts = $this->workBookParts ??
            new WorkBookParts(
                $this->archive,
            );

        return $this->workBookParts;
    }

    /**
    *   getWorkBookRels
    *
    *   @return WorkBookRels
    */
    public function getWorkBookRels(): WorkBookRels
    {
        $this->workBookRels = $this->workBookRels ??
            new WorkBookRels(
                $this->archive,
            );

        return $this->workBookRels;
    }

    /**
    *   findSheetPartsId
    *
    *   @param string $sheet_name
    *   @return string
    */
    public function findSheetPartsId(
        string $sheet_name,
    ): string {
        return $this->getWorkBookParts()
            ->findSheetPartsId(
                $sheet_name
            );
    }

    /**
    *   findSheetFileName
    *
    *   @param string $sheet_id
    *   @return string
    */
    public function findSheetFileName(
        string $sheet_id,
    ): string {
        return 'xl/' .
            $this->getWorkBookRels()
                ->findSheetFileName(
                    $sheet_id,
                );
    }
}
