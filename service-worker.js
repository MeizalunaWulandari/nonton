self.addEventListener('install', (event) => {
  console.log('Service Worker installed');
});

self.addEventListener('activate', (event) => {
  console.log('Service Worker activated');
});

// Fungsi untuk mengirim request dummy
function sendDummyRequest(url) {
  fetch(url)
    .then(response => {
      console.log('Request to ' + url + ' status:', response.status);
    })
    .catch(error => {
      console.error('Error sending dummy request:', error);
    });
}

// Mengirim request dummy secara periodic
setInterval(() => {
  const dummyURLs = [
    'https://nusantaratv.siar.us/nusantaratv/live/playlist.m3u8',
    'https://5bf7b725107e5.streamlock.net/tvkesehatan/tvkesehatan/playlist.m3u8',
    'https://5bf7b725107e5.streamlock.net/jurnaltv/jurnaltv/playlist.m3u8',
    'https://5bf7b725107e5.streamlock.net/saliratv/saliratv/playlist.m3u8',
    'https://dgwubfppws111.cloudfront.net/out/v1/667a86e35ddd496c886fa11598dc184d/index_1.m3u8',
    'https://d2p372oxiwmcn1.cloudfront.net/hls/main.m3u8',
    'https://d2e1asnsl7br7b.cloudfront.net/4c152bfc7ad2463d928bfbec0990eaeb/index.m3u8',
    'https://d18dyiwu97wm6q.cloudfront.net/playlist.m3u8',
    'https://d50a1g0nh14ou.cloudfront.net/v1/master/3722c60a815c199d9c0ef36c5b73da68a62b09d1/CJ-ENM-prod/e91c6419_2e45_4f6c_a646_b912658d73b8/hls/playlist.m3u8',
    'https://d1211whpimeups.cloudfront.net/smil:rtb1/chunklist.m3u8',
    'https://d1211whpimeups.cloudfront.net/smil:rtb2/chunklist.m3u8',
    'https://d1211whpimeups.cloudfront.net/smil:rtbgo/chunklist.m3u8',
    'https://d2kziuzkf9oizb.cloudfront.net/rd005/playlist.m3u8',
    'https://d2kziuzkf9oizb.cloudfront.net/rd003/playlist.m3u8',
    'https://d2kziuzkf9oizb.cloudfront.net/rd001/playlist.m3u8',
    'https://edge.medcom.id/live-edge/smil:mgnch.smil/playlist.m3u8',
    'https://videos-cloudfront-usp.jwpsrv.com/672f9290_9c0972ead7ad242d41cb418e24b998c29d5059a3/site/fM9jRrkn/media/3UaRR7zh/version/3UaRR7zh/manifest.ism/manifest-audio_eng=112011-video_eng=1560905.m3u8',
    'https://videos-cloudfront-usp.jwpsrv.com/672f92f3_2c8afdeea3e8b7a8b6308eee73eed1886187c737/site/fM9jRrkn/media/hF5Mvc72/version/hF5Mvc72/manifest.ism/manifest-audio_eng=112019.m3u8',
    'https://d25tgymtnqzu8s.cloudfront.net/event/smil:ag1/manifestxplay.m3u8',
    'https://d25tgymtnqzu8s.cloudfront.net/event/smil:ag2/manifestxplay.m3u8',
    'https://d25tgymtnqzu8s.cloudfront.net/event/smil:ag3/manifestxplay.m3u8',
    'https://d25tgymtnqzu8s.cloudfront.net/event/smil:ag4/manifestxplay.m3u8',
    'https://d25tgymtnqzu8s.cloudfront.net/event/smil:ag5/manifestxplay.m3u8',
    'https://d25tgymtnqzu8s.cloudfront.net/event/smil:event1/manifestxplay.m3u8'
  ];

  dummyURLs.forEach(url => {
    sendDummyRequest(url);
  });
}, 5000);  // Misalnya setiap 5 detik

self.addEventListener('fetch', (event) => {
  // Kamu bisa menangani fetch request lain di sini jika diperlukan
});
