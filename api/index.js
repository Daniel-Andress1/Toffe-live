export default async function handler(req, res) {
  // Only POST requests allowed
  if (req.method !== 'POST') {
    return res.status(405).json({ error: 'Method not allowed' });
  }

  // JSON directly in the code
  const channelJson = {
    channel: "https://live_tv.starcdnup.com/TNT_Sports_1/index.m3u8"
  };

  try {
    // Make POST request directly to beesports
    const response = await fetch('https://beesports.net/authorize-channel', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
      },
      body: JSON.stringify(channelJson)
    });

    const data = await response.json();

    res.status(200).json(data); // Return the response
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
}
