<?php

namespace StaffCollab\Email\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use StaffCollab\Email\Emailable;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\View;
use StaffCollab\Email\EmailTemplate;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use StaffCollab\Email\Filament\Resources\EmailTemplateResource\Pages\EditEmailTemplate;
use StaffCollab\Email\Filament\Resources\EmailTemplateResource\Pages\ListEmailTemplates;
use StaffCollab\Email\Filament\Resources\EmailTemplateResource\Pages\CreateEmailTemplate;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-at-symbol';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $cluster = Settings::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Trigger & Recipients')
                    ->description('Select the event that triggers this email and configure recipients and attachments.')
                    ->aside()
                    ->columns(3)
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Select::make('event_class')
                                    ->label('Event')
                                    ->options(self::getEventOptions())
                                    ->live()
                                    ->required()
                                    ->searchable(),
                            ]),
                        // Recipients and Attachments will be loaded dynamically
                        CheckboxList::make('recipient_keys')
                            ->label('Recipients')
                            ->options(fn(Get $get) => self::getRecipientOptions($get('event_class')))
                            ->columns(1)
                            ->visible(fn(Get $get) => !empty($get('event_class'))),
                        CheckboxList::make('attachment_keys')
                            ->label('Attachments')
                            ->options(fn($get) => self::getAttachmentOptions($get('event_class')))
                            ->columns(1)
                            ->visible(fn(Get $get) => !empty($get('event_class'))),
                    ]),

                View::make('email::section-border')
                    ->columnSpanFull(),

                Section::make('Email template')
                    ->description('Configure the email template details, including subject, body, and call to action.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        TextInput::make('from_name')
                            ->label('From Name')
                            ->maxLength(255)
                            ->placeholder('e.g., {{ $user->name }}'),
                        TextInput::make('reply_to')
                            ->label('Reply To')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('e.g., {{ $user->email }}'),
                        TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Welcome {{ $user->name }}!'),
                        TextInput::make('greeting')
                            ->label('Greeting')
                            ->maxLength(255)
                            ->placeholder('e.g., Hello {{ $user->name }},'),
                        TiptapEditor::make('body')
                            ->label('Email Body')
                            ->columnSpanFull()
                            ->profile('email')
                            ->tools([
                                'heading',
                                'bullet-list',
                                'ordered-list',
                                'checked-list',
                                'blockquote',
                                'hr',
                                '|',
                                'bold',
                                'italic',
                                'strike',
                                'underline',
                                'superscript',
                                'subscript',
                                'align-left',
                                'align-center',
                                'align-right',
                                '|',
                                'link',
                                'media',
                                '|',
                                'undo',
                                'redo',
                            ])
                            ->placeholder('Write your email content here. You can use variables like {{ $user->name }} for dynamic content.')
                            ->maxContentWidth('full')
                            ->required(),
                        TextInput::make('call_to_action')
                            ->label('Call to Action Text')
                            ->maxLength(255)
                            ->placeholder('e.g., Get Started'),
                        TextInput::make('call_to_action_url')
                            ->label('Call to Action URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('e.g., {{ config("app.url") }}/dashboard'),
                        TiptapEditor::make('signature')
                            ->label('Email Signature')
                            ->columnSpanFull()
                            ->profile('minimal')
                            ->tools([
                                'bold',
                                'italic',
                                'link',
                                '|',
                                'undo',
                                'redo',
                            ])
                            ->placeholder('Best regards,<br>Your Team')
                            ->maxContentWidth('full'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_class')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_class')
                    ->label('Event')
                    ->options(self::getEventOptions()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmailTemplates::route('/'),
            'create' => CreateEmailTemplate::route('/create'),
            'edit' => EditEmailTemplate::route('/{record}/edit'),
        ];
    }

    // Helper to scan app/Events and return all event classes
    public static function getAllEventClasses(): array
    {
        $eventPath = app_path('Events');
        if (! is_dir($eventPath)) {
            return [];
        }

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($eventPath));
        $classes = [];

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = str_replace(app_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $class = 'App\\' . str_replace(['/', '\\', '.php'], ['\\', '\\', ''], $relativePath);
                if (class_exists($class)) {
                    $classes[] = $class;
                }
            }
        }

        return $classes;
    }

    // Helper to get all available events implementing Emailable
    public static function getEventOptions(): array
    {
        $options = [];
        foreach (self::getAllEventClasses() as $class) {
            if (is_subclass_of($class, Emailable::class)) {
                $options[class_basename($class)] = $class::getName();
            }
        }

        return $options;
    }

    // Helper to get recipient options from the event
    public static function getRecipientOptions($eventClass): array
    {
        if (! $eventClass) {
            return [];
        }

        $class = 'App\\Events\\' . $eventClass;

        if (!class_exists($class) || !is_subclass_of($class, Emailable::class)) {
            return [];
        }

        try {
            $recipients = $class::getRecipients();
            $options = [];
            foreach ($recipients as $key => $recipient) {
                $options[$key] = $key;
            }
            return $options;
        } catch (\Exception $e) {
            return [];
        }
    }

    // Helper to get attachment options from the event
    public static function getAttachmentOptions($eventClass): array
    {
        if (! $eventClass) {
            return [];
        }

        $class = 'App\\Events\\' . $eventClass;
        
        if (!class_exists($class) || !is_subclass_of($class, Emailable::class)) {
            return [];
        }

        try {
            $attachments = $class::getAttachments();
            $options = [];
            foreach ($attachments as $key => $attachment) {
                $options[$key] = $key;
            }
            return $options;
        } catch (\Exception $e) {
            return [];
        }
    }
}