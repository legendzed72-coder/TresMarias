export const bark   = '#b65a2d';
export const barkBg = 'rgba(182, 90, 45, 0.15)';
export const gold   = '#e0a93b';
export const goldBg = 'rgba(224, 169, 59, 0.1)';
export const leaf   = '#5f8b5b';
export const leafBg = 'rgba(95, 139, 91, 0.15)';
export const muted  = '#6e5a4c';
export const grid   = 'rgba(217, 168, 122, 0.15)';

export const categoryColors = [
    '#b65a2d', '#e0a93b', '#5f8b5b', '#8f3f1b', '#d4956a',
    '#7cb87a', '#c78c3d', '#a06b49', '#6d9e6a', '#d9a87a',
];
export const categoryBgColors = categoryColors.map(c => c + '33');

export const fontFamily = 'Manrope';
export const fontOpts = { family: fontFamily, weight: '600' };

export const sharedScaleX = {
    grid: { display: false },
    ticks: { font: { family: fontFamily }, color: muted, maxRotation: 45, minRotation: 0 }
};

export const sharedScaleY = {
    position: 'left',
    grid: { color: grid },
    ticks: {
        font: { family: fontFamily }, color: muted,
        callback: function(v) { return '₱' + v.toLocaleString(); }
    }
};

export const sharedScaleY1 = {
    position: 'right',
    grid: { drawOnChartArea: false },
    ticks: { font: { family: fontFamily }, color: muted, stepSize: 1 }
};
