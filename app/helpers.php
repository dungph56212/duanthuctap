<?php

if (!function_exists('productImageUrl')) {
    /**
     * Helper function to handle product image URLs
     * 
     * @param string|null $imageUrl
     * @param string|null $default
     * @return string
     */
    function productImageUrl($imageUrl, $default = null)
    {
        if (empty($imageUrl)) {
            return $default ?: 'https://via.placeholder.com/600x800/4CAF50/FFFFFF?text=No+Image';
        }
        
        // Check if it's already a full URL
        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return $imageUrl;
        }
          // Otherwise treat as storage path
        return \Illuminate\Support\Facades\Storage::url($imageUrl);
    }
}

if (!function_exists('userAvatarUrl')) {
    /**
     * Helper function to handle user avatar URLs
     * 
     * @param string|null $avatar
     * @param string|null $default
     * @return string
     */
    function userAvatarUrl($avatar, $default = null)
    {
        if (empty($avatar)) {
            return $default ?: 'https://ui-avatars.com/api/?name=User&background=007bff&color=fff&size=200';
        }
        
        // Check if it's already a full URL
        if (filter_var($avatar, FILTER_VALIDATE_URL)) {
            return $avatar;
        }
        
        // Otherwise treat as storage path
        return \Illuminate\Support\Facades\Storage::url($avatar);
    }
}

if (!function_exists('userNameInitials')) {
    /**
     * Helper function to get user name initials for avatar
     * 
     * @param string $name
     * @return string
     */
    function userNameInitials($name)
    {
        $words = explode(' ', trim($name));
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }
        
        return $initials ?: 'U';
    }
}
