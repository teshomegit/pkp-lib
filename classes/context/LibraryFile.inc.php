<?php

/**
 * @file classes/context/LibraryFile.inc.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class LibraryFile
 * @ingroup context
 *
 * @see LibraryFileDAO
 *
 * @brief Library file class.
 */

namespace PKP\context;

use FileManager;

import('lib.pkp.classes.file.FileManager');
use PKP\config\Config;

class LibraryFile extends \PKP\core\DataObject
{
    public const LIBRARY_FILE_TYPE_CONTRACT = 1;
    public const LIBRARY_FILE_TYPE_MARKETING = 2;
    public const LIBRARY_FILE_TYPE_PERMISSION = 3;
    public const LIBRARY_FILE_TYPE_REPORT = 4;
    public const LIBRARY_FILE_TYPE_OTHER = 5;

    /**
     * Return absolute path to the file on the host filesystem.
     *
     * @return string
     */
    public function getFilePath()
    {
        $contextId = $this->getContextId();

        return Config::getVar('files', 'files_dir') . '/contexts/' . $contextId . '/library/' . $this->getServerFileName();
    }

    //
    // Get/set methods
    //
    /**
     * Get ID of context.
     *
     * @return int
     */
    public function getContextId()
    {
        return $this->getData('contextId');
    }

    /**
     * Set ID of context.
     *
     * @param $contextId int
     */
    public function setContextId($contextId)
    {
        $this->setData('contextId', $contextId);
    }

    /**
     * Get ID of submission.
     *
     * @return int
     */
    public function getSubmissionId()
    {
        return $this->getData('submissionId');
    }

    /**
     * Set ID of submission.
     */
    public function setSubmissionId($submissionId)
    {
        $this->setData('submissionId', $submissionId);
    }

    /**
     * Get server-side file name of the file.
     *
     * @param return string
     */
    public function getServerFileName()
    {
        return $this->getData('fileName');
    }

    /**
     * Set server-side file name of the file.
     *
     * @param $fileName string
     */
    public function setServerFileName($fileName)
    {
        $this->setData('fileName', $fileName);
    }

    /**
     * Get original file name of the file.
     *
     * @param return string
     */
    public function getOriginalFileName()
    {
        return $this->getData('originalFileName');
    }

    /**
     * Set original file name of the file.
     *
     * @param $originalFileName string
     */
    public function setOriginalFileName($originalFileName)
    {
        $this->setData('originalFileName', $originalFileName);
    }

    /**
     * Set the name of the file
     *
     * @param $name string
     * @param $locale string
     */
    public function setName($name, $locale)
    {
        $this->setData('name', $name, $locale);
    }

    /**
     * Get the name of the file
     *
     * @param $locale string
     *
     * @return string
     */
    public function getName($locale)
    {
        return $this->getData('name', $locale);
    }

    /**
     * Get the localized name of the file
     *
     * @return string
     */
    public function getLocalizedName()
    {
        return $this->getLocalizedData('name');
    }

    /**
     * Get file type of the file.
     *
     * @ return string
     */
    public function getFileType()
    {
        return $this->getData('fileType');
    }

    /**
     * Set file type of the file.
     *
     * @param $fileType string
     */
    public function setFileType($fileType)
    {
        $this->setData('fileType', $fileType);
    }

    /**
     * Get type of the file.
     *
     * @ return string
     */
    public function getType()
    {
        return $this->getData('type');
    }

    /**
     * Set type of the file.
     *
     * @param $type string
     */
    public function setType($type)
    {
        $this->setData('type', $type);
    }

    /**
     * Get uploaded date of file.
     *
     * @return date
     */
    public function getDateUploaded()
    {
        return $this->getData('dateUploaded');
    }

    /**
     * Set uploaded date of file.
     *
     * @param $dateUploaded date
     */
    public function setDateUploaded($dateUploaded)
    {
        return $this->SetData('dateUploaded', $dateUploaded);
    }

    /**
     * Get modified date of file.
     *
     * @return date
     */
    public function getDateModified()
    {
        return $this->getData('dateModified');
    }

    /**
     * Set modified date of file.
     *
     * @param $dateModified date
     */
    public function setDateModified($dateModified)
    {
        return $this->SetData('dateModified', $dateModified);
    }

    /**
     * Get file size of file.
     *
     * @return int
     */
    public function getFileSize()
    {
        return $this->getData('fileSize');
    }


    /**
     * Set file size of file.
     *
     * @param $fileSize int
     */
    public function setFileSize($fileSize)
    {
        return $this->SetData('fileSize', $fileSize);
    }

    /**
     * Get nice file size of file.
     *
     * @return string
     */
    public function getNiceFileSize()
    {
        $fileManager = new FileManager();
        return $fileManager->getNiceFileSize($this->getData('fileSize'));
    }

    /**
     * Get the file's document type (enumerated types)
     *
     * @return string
     */
    public function getDocumentType()
    {
        $fileManager = new FileManager();
        return $fileManager->getDocumentType($this->getFileType());
    }

    /**
     * Get public access indication
     *
     * @return boolean
     */
    public function getPublicAccess()
    {
        return $this->getData('publicAccess');
    }

    /**
     * Set public access indication
     *
     * @param $publicAccess boolean
     */
    public function setPublicAccess($publicAccess)
    {
        $this->setData('publicAccess', $publicAccess);
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\PKP\context\LibraryFile', '\LibraryFile');
    foreach ([
        'LIBRARY_FILE_TYPE_CONTRACT',
        'LIBRARY_FILE_TYPE_MARKETING',
        'LIBRARY_FILE_TYPE_PERMISSION',
        'LIBRARY_FILE_TYPE_REPORT',
        'LIBRARY_FILE_TYPE_OTHER',
    ] as $constantName) {
        if (!defined($constantName)) {
            define($constantName, constant('LibraryFile::' . $constantName));
        }
    }
}
