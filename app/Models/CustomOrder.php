<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'whatsapp_number',
        'category',
        'product',
        'product_id',
        'other_product_name',
        'quantity',
        'color',
        'size',
        'material_type',
        'material',
        'production_technique',
        'deadline',
        'design_file',
        'user_design_path',
        'admin_mockup_file',
        'admin_design_path',
        'price_per_item',
        'notes',
        'revision_notes',
        'status',
        'revision_count',
        'transaction_id',
    ];

    protected $casts = [
        'deadline' => 'date',
        'quantity' => 'integer',
        'revision_count' => 'integer',
        'price_per_item' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the custom order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the basis product that this custom order belongs to.
     */
    public function product_relation(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the transaction linked to this custom order.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the revisions / messages for this custom order.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(CustomOrderRevision::class);
    }

    /**
     * Get human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu_review' => 'Menunggu Review',
            'menunggu_persetujuan_customer' => 'Menunggu Persetujuan Customer',
            'revisi_desain' => 'Revisi Pesanan',
            'diproses' => 'Diproses',
            'produksi' => 'Produksi',
            'siap_diambil_dikirim' => 'Siap Diambil / Dikirim',
            'selesai' => 'Selesai',
            
            // Reza's statuses
            'pending' => 'Pending',
            'designing' => 'Sedang Didesain',
            'revision' => 'Revisi',
            'approved' => 'Disetujui (Menunggu Bayar)',
            'completed' => 'Selesai (Sudah Checkout)',
            'rejected' => 'Ditolak',
            
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Get status badge CSS classes (vanilla tailwind compatible).
     */
    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'menunggu_review' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
            'menunggu_persetujuan_customer' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
            'revisi_desain' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            'diproses' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'produksi' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
            'siap_diambil_dikirim' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            'selesai' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',

            // Reza's statuses
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500',
            'designing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500',
            'revision' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-500',
            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500',
            'completed' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500',

            default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
        };
    }
}
