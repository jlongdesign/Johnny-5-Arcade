/**
 * Console Utils Module
 * Provides mappings and utilities for different gaming systems
 */

const ConsoleUtils = {
    /**
     * System core mappings for RetroArch
     */
    systemCores: {
        'nes': 'fceumm',
        'snes': 'snes9x',
        'gb': 'gambatte',
        'gbc': 'gambatte',
        'gba': 'mgba',
        'genesis': 'genesis_plus_gx',
        'megadrive': 'genesis_plus_gx',
        'arcade': 'mame2003',
        'n64': 'mupen64plus_next',
        'psx': 'pcsx_rearmed',
        'ps1': 'pcsx_rearmed'
    },

    /**
     * EmulatorJS system mappings
     */
    emulatorJSSystems: {
        'nes': 'nes',
        'snes': 'snes',
        'gb': 'gb',
        'gbc': 'gbc',
        'gba': 'gba',
        'genesis': 'segaMD',
        'megadrive': 'segaMD',
        'arcade': 'arcade',
        'n64': 'n64',
        'psx': 'psx',
        'ps1': 'psx'
    },

    /**
     * System display names
     */
    systemNames: {
        'nes': 'Nintendo Entertainment System',
        'snes': 'Super Nintendo Entertainment System',
        'gb': 'Game Boy',
        'gbc': 'Game Boy Color',
        'gba': 'Game Boy Advance',
        'genesis': 'Sega Genesis',
        'megadrive': 'Sega Mega Drive',
        'arcade': 'Arcade',
        'n64': 'Nintendo 64',
        'psx': 'Sony PlayStation',
        'ps1': 'Sony PlayStation'
    },

    /**
     * File extensions for each system
     */
    systemExtensions: {
        'nes': ['.nes'],
        'snes': ['.smc', '.sfc'],
        'gb': ['.gb'],
        'gbc': ['.gbc'],
        'gba': ['.gba'],
        'genesis': ['.md', '.gen', '.bin'],
        'megadrive': ['.md', '.gen', '.bin'],
        'arcade': ['.zip'],
        'n64': ['.z64', '.n64'],
        'psx': ['.iso', '.cue', '.bin'],
        'ps1': ['.iso', '.cue', '.bin']
    },

    /**
     * Get RetroArch core for a system
     */
    getCoreForSystem(system) {
        return this.systemCores[system.toLowerCase()] || 'fceumm';
    },

    /**
     * Get EmulatorJS system name
     */
    getEmulatorJSSystem(system) {
        return this.emulatorJSSystems[system.toLowerCase()] || 'nes';
    },

    /**
     * Get display name for system
     */
    getSystemDisplayName(system) {
        return this.systemNames[system.toLowerCase()] || system.toUpperCase();
    },

    /**
     * Get valid extensions for system
     */
    getSystemExtensions(system) {
        return this.systemExtensions[system.toLowerCase()] || [];
    },

    /**
     * Detect system from file extension
     */
    detectSystemFromExtension(filename) {
        const extension = filename.toLowerCase().match(/\.[^.]+$/);
        if (!extension) return 'nes';

        const ext = extension[0];
        
        for (const [system, extensions] of Object.entries(this.systemExtensions)) {
            if (extensions.includes(ext)) {
                return system;
            }
        }
        
        return 'nes'; // Default fallback
    },

    /**
     * Validate ROM file extension
     */
    isValidROMFile(filename) {
        const extension = filename.toLowerCase().match(/\.[^.]+$/);
        if (!extension) return false;

        const ext = extension[0];
        const allExtensions = Object.values(this.systemExtensions).flat();
        
        return allExtensions.includes(ext);
    },

    /**
     * Get recommended emulator service for system
     */
    getRecommendedService(system) {
        const servicePreferences = {
            'arcade': 'archive.org',
            'nes': 'emulatorjs',
            'snes': 'emulatorjs',
            'gb': 'emulatorjs',
            'gbc': 'emulatorjs',
            'gba': 'emulatorjs',
            'genesis': 'emulatorjs',
            'n64': 'archive.org',
            'psx': 'archive.org'
        };
        
        return servicePreferences[system.toLowerCase()] || 'emulatorjs';
    }
};

// Make globally available
window.ConsoleUtils = ConsoleUtils;