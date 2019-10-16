import { Component, ElementRef, EventEmitter, Input, OnChanges, OnInit, Output, ViewChild } from '@angular/core';
import { UploadMetadata } from './before-upload.interface';

import { ImageService } from './image.service';

export class FileHolder {
    public pending = false;
    public serverResponse: { status: number, response: any };

    constructor(public src: string, public file: File) {
    }
}

@Component({
    selector: 'app-upload-photo',
    templateUrl: './upload-photo.component.html',
    styleUrls: ['./upload-photo.component.scss']
})
export class UploadPhotoComponent implements OnInit, OnChanges {
    files: FileHolder[] = [];
    fileCounter = 0;
    fileOver = false;
    showFileTooLargeMessage = false;
    uploadedResponse: Array<any> = [];

    @Input() disabled = false; // disable remove img
    @Input() fileTooLargeMessage;
    @Input() headers: { [name: string]: any };
    @Input() max = 100;
    @Input() maxFileSize: number;
    @Input() preview = true;
    @Input() supportedExtensions: string[];
    @Input() url: string;
    @Input() withCredentials = false;
    @Input() uploadedFiles: string[] | Array<{ url: string, fileName: string, blob?: Blob }> = [];
    @Input() title: string;
    @Input() isMultiple;
    @Input() existingPhotos;
    @Output() removed = new EventEmitter<FileHolder>();
    @Output() uploadStateChanged = new EventEmitter<boolean>();
    @Output() uploadFinished = new EventEmitter<FileHolder>();
    @Output() previewClicked = new EventEmitter<FileHolder>();
    @Output() changedPhoto = new EventEmitter<FileHolder[]>();
    
    @ViewChild('input') private inputElement: ElementRef;
    private pendingFilesCounter = 0;

    constructor(private imageService: ImageService) {
    }

    @Input() beforeUpload: (param: UploadMetadata) => UploadMetadata | Promise<UploadMetadata> = data => data;

    ngOnInit() {
        if (!this.fileTooLargeMessage) {
            this.fileTooLargeMessage = 'An image was too large and was not uploaded.' +
                (this.maxFileSize ? (' The maximum file size is ' + this.maxFileSize / 1024) + 'KiB.' : '');
        }

        if (this.isMultiple === false) {
            this.max = 1;
        }
        
        this.supportedExtensions = this.supportedExtensions ? this.supportedExtensions.map((ext) => 'image/' + ext) : ['image/*'];
    }

    openSelectBox() {
        this.inputElement.nativeElement.click();
    }

    deleteAll() {
        this.files.forEach(f => this.removed.emit(f));
        this.files = [];
        this.fileCounter = 0;
        this.inputElement.nativeElement.value = '';
        this.changedPhoto.emit(this.files);
    }

    deleteFile(file: FileHolder): void {
        const index = this.files.indexOf(file);
        this.files.splice(index, 1);
        this.fileCounter--;
        this.inputElement.nativeElement.value = '';
        this.removed.emit(file);
        this.changedPhoto.emit(this.files);
    }

    previewFileClicked(file: FileHolder) {
        this.previewClicked.emit(file);
    }

    ngOnChanges(changes) {
        if (changes.uploadedFiles && changes.uploadedFiles.currentValue.length > 0) {
            this.processUploadedFiles();
        }
    }

    onFileChange(files: FileList) {
        if (this.disabled) {
            return;
        }

        const remainingSlots = this.countRemainingSlots();
        const filesToUploadNum = files.length > remainingSlots ? remainingSlots : files.length;

        if (this.url && filesToUploadNum !== 0) {
            this.uploadStateChanged.emit(true);
        }

        this.fileCounter += filesToUploadNum;
        this.showFileTooLargeMessage = false;
        this.uploadFiles(files, filesToUploadNum);
    }

    onFileOver = (isOver) => {
        this.fileOver = isOver;
    };

    private countRemainingSlots = () => this.max - this.fileCounter;

    private onResponse(response: any, fileHolder: FileHolder) {
        fileHolder.serverResponse = { status: response.status, response };
        fileHolder.pending = false;

        this.uploadFinished.emit(fileHolder);
        this.changedPhoto.emit(this.files);

        if (--this.pendingFilesCounter === 0) {
            this.uploadStateChanged.emit(false);
        }
    }

    private processUploadedFiles() {
        for (let i = 0; i < this.uploadedFiles.length; i++) {
            const data: any = this.uploadedFiles[i];

            let fileBlob: Blob,
                file: File,
                fileUrl: string;

            if (data instanceof Object) {
                fileUrl = data.url;
                fileBlob = (data.blob) ? data.blob : new Blob([data]);
                file = new File([fileBlob], data.fileName);
            } else {
                fileUrl = data;
                fileBlob = new Blob([fileUrl]);
                file = new File([fileBlob], fileUrl);
            }

            this.files.push(new FileHolder(fileUrl, file));
        }
    }

    private async uploadFiles(files: FileList, filesToUploadNum: number) {
        for (let i = 0; i < filesToUploadNum; i++) {
            const file = files[i];

            if (this.maxFileSize && file.size > this.maxFileSize) {
                this.fileCounter--;
                this.inputElement.nativeElement.value = '';
                this.showFileTooLargeMessage = true;
                continue;
            }

            const beforeUploadResult: UploadMetadata = await this.beforeUpload({ file, url: this.url, abort: false });

            if (beforeUploadResult.abort) {
                this.fileCounter--;
                this.inputElement.nativeElement.value = '';
                continue;
            }

            const img = document.createElement('img');
            img.src = window.URL.createObjectURL(beforeUploadResult.file);

            const reader = new FileReader();
            reader.addEventListener('load', (event: any) => {
                const fileHolder: FileHolder = new FileHolder(event.target.result, beforeUploadResult.file);
                this.uploadSingleFile(fileHolder, beforeUploadResult.url, beforeUploadResult.formData);
                this.files.push(fileHolder);
            }, false);
            reader.readAsDataURL(beforeUploadResult.file);
        }
    }

    private uploadSingleFile(fileHolder: FileHolder, url = this.url, customForm?: { [name: string]: any }) {
        // if (url) {
        this.pendingFilesCounter++;
        fileHolder.pending = true;

        console.log(this.isMultiple);
        
        // Temporaly fix for demo
        // When edit, delete old cover page because only 1 exists
        if (this.isMultiple === false) {
            console.log('come deleeteall');
            
            this.deleteAll();
        }
        this.imageService
            .postImage(fileHolder.file)
            .subscribe(
                response => this.onResponse(response, fileHolder),
                error => {
                    this.onResponse(error, fileHolder);
                    this.deleteFile(fileHolder);
                });
        // } else {
        //     this.uploadFinished.emit(fileHolder);
        // }
    }
}
