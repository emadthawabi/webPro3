document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease',
        once: true
    });

    // Get DOM elements
    const continentSelect = document.getElementById('continent-select');
    const countrySelect = document.getElementById('country-select');
    const citySelect = document.getElementById('city-select');
    const resultsWrapper = document.getElementById('results-wrapper');
    const initialState = document.getElementById('initial-state');
    const resultsGrid = document.getElementById('results-grid');
    const cityNameSpan = document.getElementById('city-name');
    const resultsTitle = document.getElementById('results-title');
    const resultsSubtitle = document.getElementById('results-subtitle');
    const noResults = document.getElementById('no-results');

    // Remove the search button from HTML as it's no longer needed
    const searchBtn = document.getElementById('search-btn');
    if (searchBtn) {
        searchBtn.style.display = 'none';
    }

    // Data structure for continents, countries, and cities
    const locationData = {
        'europe': {
            name: 'Europe',
            attractions: [
                {
                    name: 'Eiffel Tower',
                    description: 'Iconic iron tower in Paris, France',
                    image: 'https://images.pexels.com/photos/699466/pexels-photo-699466.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Landmark',
                    rating: 4.7,
                    price: '€25',
                    country: 'france',
                    city: 'paris'
                },
                {
                    name: 'Colosseum',
                    description: 'Ancient amphitheater in Rome, Italy',
                    image: 'https://images.pexels.com/photos/532263/pexels-photo-532263.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Historic Site',
                    rating: 4.8,
                    price: '€16',
                    country: 'italy',
                    city: 'rome'
                },
                {
                    name: 'Louvre Museum',
                    description: 'World\'s largest art museum in Paris, France',
                    image: 'https://images.pexels.com/photos/2363/france-landmark-lights-night.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Museum',
                    rating: 4.8,
                    price: '€17',
                    country: 'france',
                    city: 'paris'
                },
                {
                    name: 'Trevi Fountain',
                    description: 'Baroque fountain in Rome, Italy',
                    image: 'https://images.pexels.com/photos/2972998/pexels-photo-2972998.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Landmark',
                    rating: 4.8,
                    price: 'Free',
                    country: 'italy',
                    city: 'rome'
                },
                {
                    name: 'Promenade des Anglais',
                    description: 'Famous promenade in Nice, France',
                    image: 'https://images.pexels.com/photos/16246264/pexels-photo-16246264/free-photo-of-view-of-mediterranean-sea-from-nice-france.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Landmark',
                    rating: 4.6,
                    price: 'Free',
                    country: 'france',
                    city: 'nice'
                },
                {
                    name: 'Grand Canal',
                    description: 'Main waterway in Venice, Italy',
                    image: 'https://images.pexels.com/photos/5009912/pexels-photo-5009912.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Landmark',
                    rating: 4.9,
                    price: 'Free',
                    country: 'italy',
                    city: 'venice'
                }
            ],
            countries: {
                'france': {
                    name: 'France',
                    cities: {
                        'paris': {
                            name: 'Paris',
                            attractions: [
                                {
                                    name: 'Eiffel Tower',
                                    description: 'Iconic iron tower on the Champ de Mars, named after engineer Gustave Eiffel.',
                                    image: 'https://images.pexels.com/photos/699466/pexels-photo-699466.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.7,
                                    price: '€25'
                                },
                                {
                                    name: 'Louvre Museum',
                                    description: 'World\'s largest art museum and historic monument housing the Mona Lisa.',
                                    image: 'https://images.pexels.com/photos/2363/france-landmark-lights-night.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Museum',
                                    rating: 4.8,
                                    price: '€17'
                                },
                                {
                                    name: 'Notre-Dame Cathedral',
                                    description: 'Medieval Catholic cathedral on the Île de la Cité known for its French Gothic architecture.',
                                    image: 'https://images.pexels.com/photos/15760151/pexels-photo-15760151/free-photo-of-notre-dame-cathedral-in-paris-france.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Religious Site',
                                    rating: 4.7,
                                    price: 'Free'
                                },
                                {
                                    name: 'Montmartre',
                                    description: 'Large hill in Paris\'s 18th arrondissement known for its artistic history and the Sacré-Cœur Basilica.',
                                    image: 'https://images.pexels.com/photos/705764/pexels-photo-705764.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'District',
                                    rating: 4.6,
                                    price: 'Free'
                                }
                            ]
                        },
                        'nice': {
                            name: 'Nice',
                            attractions: [
                                {
                                    name: 'Promenade des Anglais',
                                    description: 'Famous promenade along the Mediterranean coastline.',
                                    image: 'https://images.pexels.com/photos/16246264/pexels-photo-16246264/free-photo-of-view-of-mediterranean-sea-from-nice-france.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.6,
                                    price: 'Free'
                                },
                                {
                                    name: 'Old Town (Vieux Nice)',
                                    description: 'Charming old district with narrow streets, colorful buildings, and local markets.',
                                    image: 'https://images.pexels.com/photos/15708650/pexels-photo-15708650/free-photo-of-old-town-in-nice-france.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'District',
                                    rating: 4.7,
                                    price: 'Free'
                                }
                            ]
                        }
                    }
                },
                'italy': {
                    name: 'Italy',
                    cities: {
                        'rome': {
                            name: 'Rome',
                            attractions: [
                                {
                                    name: 'Colosseum',
                                    description: 'Ancient amphitheater in the center of Rome, largest ever built.',
                                    image: 'https://images.pexels.com/photos/532263/pexels-photo-532263.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Historic Site',
                                    rating: 4.8,
                                    price: '€16'
                                },
                                {
                                    name: 'Vatican Museums',
                                    description: 'Museums displaying works from the extensive collection of the Catholic Church.',
                                    image: 'https://images.pexels.com/photos/142931/pexels-photo-142931.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Museum',
                                    rating: 4.7,
                                    price: '€17'
                                },
                                {
                                    name: 'Trevi Fountain',
                                    description: 'Baroque fountain designed by Italian architect Nicola Salvi.',
                                    image: 'https://images.pexels.com/photos/2972998/pexels-photo-2972998.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.8,
                                    price: 'Free'
                                }
                            ]
                        },
                        'venice': {
                            name: 'Venice',
                            attractions: [
                                {
                                    name: 'Grand Canal',
                                    description: 'Main waterway through the central districts of Venice.',
                                    image: 'https://images.pexels.com/photos/5009912/pexels-photo-5009912.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.9,
                                    price: 'Free'
                                },
                                {
                                    name: 'St. Mark\'s Basilica',
                                    description: 'Cathedral church of the Roman Catholic Archdiocese of Venice.',
                                    image: 'https://images.pexels.com/photos/3566187/pexels-photo-3566187.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Religious Site',
                                    rating: 4.8,
                                    price: '€3'
                                }
                            ]
                        }
                    }
                }
            }
        },
        'asia': {
            name: 'Asia',
            attractions: [
                {
                    name: 'Tokyo Skytree',
                    description: 'Tallest tower in Japan and second tallest structure in the world.',
                    image: 'https://images.pexels.com/photos/2506923/pexels-photo-2506923.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Landmark',
                    rating: 4.5,
                    price: '¥2,100',
                    country: 'japan',
                    city: 'tokyo'
                },
                {
                    name: 'Grand Palace',
                    description: 'Former residence of the Kings of Siam in Bangkok, Thailand',
                    image: 'https://images.pexels.com/photos/1010657/pexels-photo-1010657.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Historic Site',
                    rating: 4.7,
                    price: '฿500',
                    country: 'thailand',
                    city: 'bangkok'
                },
                {
                    name: 'Fushimi Inari Shrine',
                    description: 'Famous shrine with thousands of vermilion torii gates in Kyoto, Japan',
                    image: 'https://images.pexels.com/photos/5961917/pexels-photo-5961917.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Religious Site',
                    rating: 4.9,
                    price: 'Free',
                    country: 'japan',
                    city: 'kyoto'
                }
            ],
            countries: {
                'japan': {
                    name: 'Japan',
                    cities: {
                        'tokyo': {
                            name: 'Tokyo',
                            attractions: [
                                {
                                    name: 'Tokyo Skytree',
                                    description: 'Tallest tower in Japan and second tallest structure in the world.',
                                    image: 'https://images.pexels.com/photos/2506923/pexels-photo-2506923.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.5,
                                    price: '¥2,100'
                                },
                                {
                                    name: 'Senso-ji Temple',
                                    description: 'Ancient Buddhist temple located in Asakusa.',
                                    image: 'https://images.pexels.com/photos/3400900/pexels-photo-3400900.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Religious Site',
                                    rating: 4.7,
                                    price: 'Free'
                                },
                                {
                                    name: 'Shibuya Crossing',
                                    description: 'Famous pedestrian scramble said to be the busiest in the world.',
                                    image: 'https://images.pexels.com/photos/2614818/pexels-photo-2614818.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.6,
                                    price: 'Free'
                                }
                            ]
                        },
                        'kyoto': {
                            name: 'Kyoto',
                            attractions: [
                                {
                                    name: 'Fushimi Inari Shrine',
                                    description: 'Famous shrine with thousands of vermilion torii gates.',
                                    image: 'https://images.pexels.com/photos/5961917/pexels-photo-5961917.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Religious Site',
                                    rating: 4.9,
                                    price: 'Free'
                                },
                                {
                                    name: 'Kinkaku-ji (Golden Pavilion)',
                                    description: 'Zen Buddhist temple covered in gold leaf.',
                                    image: 'https://images.pexels.com/photos/3400975/pexels-photo-3400975.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Religious Site',
                                    rating: 4.8,
                                    price: '¥400'
                                }
                            ]
                        }
                    }
                },
                'thailand': {
                    name: 'Thailand',
                    cities: {
                        'bangkok': {
                            name: 'Bangkok',
                            attractions: [
                                {
                                    name: 'Grand Palace',
                                    description: 'Complex of buildings that served as the official residence of the Kings of Siam.',
                                    image: 'https://images.pexels.com/photos/1010657/pexels-photo-1010657.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Historic Site',
                                    rating: 4.7,
                                    price: '฿500'
                                },
                                {
                                    name: 'Wat Arun',
                                    description: 'Buddhist temple on the west bank of the Chao Phraya River.',
                                    image: 'https://images.pexels.com/photos/18525909/pexels-photo-18525909/free-photo-of-wat-arun-in-bangkok-thailand.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Religious Site',
                                    rating: 4.6,
                                    price: '฿50'
                                }
                            ]
                        }
                    }
                }
            }
        },
        'north-america': {
            name: 'North America',
            attractions: [
                {
                    name: 'Statue of Liberty',
                    description: 'Colossal neoclassical sculpture in New York, USA',
                    image: 'https://images.pexels.com/photos/64271/statue-of-liberty-landmark-america-immigration-64271.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Landmark',
                    rating: 4.7,
                    price: '$25',
                    country: 'usa',
                    city: 'new-york'
                },
                {
                    name: 'CN Tower',
                    description: 'Communication and observation tower in Toronto, Canada',
                    image: 'https://images.pexels.com/photos/325185/pexels-photo-325185.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Landmark',
                    rating: 4.6,
                    price: 'CA$38',
                    country: 'canada',
                    city: 'toronto'
                },
                {
                    name: 'Golden Gate Bridge',
                    description: 'Suspension bridge in San Francisco, USA',
                    image: 'https://images.pexels.com/photos/208745/pexels-photo-208745.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Landmark',
                    rating: 4.8,
                    price: 'Free',
                    country: 'usa',
                    city: 'san-francisco'
                }
            ],
            countries: {
                'usa': {
                    name: 'United States',
                    cities: {
                        'new-york': {
                            name: 'New York',
                            attractions: [
                                {
                                    name: 'Statue of Liberty',
                                    description: 'Colossal neoclassical sculpture on Liberty Island in New York Harbor.',
                                    image: 'https://images.pexels.com/photos/64271/statue-of-liberty-landmark-america-immigration-64271.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.7,
                                    price: '$25'
                                },
                                {
                                    name: 'Central Park',
                                    description: 'Urban park in Manhattan spanning 843 acres.',
                                    image: 'https://images.pexels.com/photos/1563256/pexels-photo-1563256.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Park',
                                    rating: 4.8,
                                    price: 'Free'
                                },
                                {
                                    name: 'Empire State Building',
                                    description: 'Art Deco skyscraper in Midtown Manhattan, a symbol of New York City.',
                                    image: 'https://images.pexels.com/photos/2193300/pexels-photo-2193300.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.7,
                                    price: '$38'
                                }
                            ]
                        },
                        'san-francisco': {
                            name: 'San Francisco',
                            attractions: [
                                {
                                    name: 'Golden Gate Bridge',
                                    description: 'Suspension bridge spanning the Golden Gate strait.',
                                    image: 'https://images.pexels.com/photos/208745/pexels-photo-208745.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.8,
                                    price: 'Free'
                                },
                                {
                                    name: 'Alcatraz Island',
                                    description: 'Historic former federal prison on an island in San Francisco Bay.',
                                    image: 'https://images.pexels.com/photos/1766215/pexels-photo-1766215.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Historic Site',
                                    rating: 4.7,
                                    price: '$40'
                                }
                            ]
                        }
                    }
                },
                'canada': {
                    name: 'Canada',
                    cities: {
                        'toronto': {
                            name: 'Toronto',
                            attractions: [
                                {
                                    name: 'CN Tower',
                                    description: 'Communication and observation tower standing 553.3 m tall.',
                                    image: 'https://images.pexels.com/photos/325185/pexels-photo-325185.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.6,
                                    price: 'CA$38'
                                },
                                {
                                    name: 'Royal Ontario Museum',
                                    description: 'Museum of art, world culture, and natural history.',
                                    image: 'https://images.pexels.com/photos/5273544/pexels-photo-5273544.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Museum',
                                    rating: 4.7,
                                    price: 'CA$23'
                                }
                            ]
                        }
                    }
                }
            }
        },
        'south-america': {
            name: 'South America',
            attractions: [
                {
                    name: 'Christ the Redeemer',
                    description: 'Art Deco statue of Jesus Christ in Rio de Janeiro, Brazil',
                    image: 'https://images.pexels.com/photos/2868242/pexels-photo-2868242.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Landmark',
                    rating: 4.8,
                    price: 'R$46',
                    country: 'brazil',
                    city: 'rio-de-janeiro'
                },
                {
                    name: 'Machu Picchu',
                    description: 'Ancient Incan citadel in the Andes Mountains of Peru',
                    image: 'https://images.pexels.com/photos/2929906/pexels-photo-2929906.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Historic Site',
                    rating: 4.9,
                    price: 'S/152',
                    country: 'peru',
                    city: 'cusco'
                },
                {
                    name: 'Iguazu Falls',
                    description: 'System of waterfalls on the border of Argentina and Brazil',
                    image: 'https://images.pexels.com/photos/1647962/pexels-photo-1647962.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Natural Wonder',
                    rating: 4.9,
                    price: 'AR$4,000',
                    country: 'argentina',
                    city: 'puerto-iguazu'
                }
            ],
            countries: {
                'brazil': {
                    name: 'Brazil',
                    cities: {
                        'rio-de-janeiro': {
                            name: 'Rio de Janeiro',
                            attractions: [
                                {
                                    name: 'Christ the Redeemer',
                                    description: 'Art Deco statue of Jesus Christ at the summit of Mount Corcovado.',
                                    image: 'https://images.pexels.com/photos/2868242/pexels-photo-2868242.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Landmark',
                                    rating: 4.8,
                                    price: 'R$46'
                                },
                                {
                                    name: 'Copacabana Beach',
                                    description: 'Famous beach known for its 4km balneario beach and vibrant atmosphere.',
                                    image: 'https://images.pexels.com/photos/2413613/pexels-photo-2413613.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Beach',
                                    rating: 4.7,
                                    price: 'Free'
                                },
                                {
                                    name: 'Sugarloaf Mountain',
                                    description: 'Peak situated at the mouth of Guanabara Bay, rising 396 meters above sea level.',
                                    image: 'https://images.pexels.com/photos/1879219/pexels-photo-1879219.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Natural Wonder',
                                    rating: 4.8,
                                    price: 'R$120'
                                }
                            ]
                        },
                        'sao-paulo': {
                            name: 'São Paulo',
                            attractions: [
                                {
                                    name: 'Ibirapuera Park',
                                    description: 'Urban park spanning 158 hectares with museums, a music hall, and gardens.',
                                    image: 'https://images.pexels.com/photos/4304776/pexels-photo-4304776.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Park',
                                    rating: 4.7,
                                    price: 'Free'
                                },
                                {
                                    name: 'Pinacoteca do Estado',
                                    description: 'One of the most important art museums in Brazil focusing on Brazilian art.',
                                    image: 'https://images.pexels.com/photos/4593081/pexels-photo-4593081.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Museum',
                                    rating: 4.8,
                                    price: 'R$30'
                                }
                            ]
                        }
                    }
                },
                'argentina': {
                    name: 'Argentina',
                    cities: {
                        'buenos-aires': {
                            name: 'Buenos Aires',
                            attractions: [
                                {
                                    name: 'Casa Rosada',
                                    description: 'The executive mansion and office of the President of Argentina.',
                                    image: 'https://images.pexels.com/photos/16794976/pexels-photo-16794976/free-photo-of-casa-rosada-in-buenos-aires-argentina.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Historic Site',
                                    rating: 4.6,
                                    price: 'Free'
                                },
                                {
                                    name: 'La Boca',
                                    description: 'Colorful neighborhood known for the football stadium La Bombonera and tango dancing.',
                                    image: 'https://images.pexels.com/photos/16795030/pexels-photo-16795030/free-photo-of-la-boca-in-buenos-aires-argentina.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'District',
                                    rating: 4.5,
                                    price: 'Free'
                                }
                            ]
                        },
                        'puerto-iguazu': {
                            name: 'Puerto Iguazu',
                            attractions: [
                                {
                                    name: 'Iguazu Falls',
                                    description: 'One of the largest and most impressive waterfall systems in the world.',
                                    image: 'https://images.pexels.com/photos/1647962/pexels-photo-1647962.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Natural Wonder',
                                    rating: 4.9,
                                    price: 'AR$4,000'
                                }
                            ]
                        }
                    }
                },
                'peru': {
                    name: 'Peru',
                    cities: {
                        'lima': {
                            name: 'Lima',
                            attractions: [
                                {
                                    name: 'Plaza Mayor',
                                    description: 'Historic center of Lima featuring colonial architecture and important buildings.',
                                    image: 'https://images.pexels.com/photos/5695626/pexels-photo-5695626.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Historic Site',
                                    rating: 4.6,
                                    price: 'Free'
                                },
                                {
                                    name: 'Huaca Pucllana',
                                    description: 'Great adobe and clay pyramid built during the Lima culture period.',
                                    image: 'https://images.pexels.com/photos/8120446/pexels-photo-8120446.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Historic Site',
                                    rating: 4.7,
                                    price: 'S/15'
                                }
                            ]
                        },
                        'cusco': {
                            name: 'Cusco',
                            attractions: [
                                {
                                    name: 'Machu Picchu',
                                    description: '15th-century Inca citadel situated on a mountain ridge 2,430 meters above sea level.',
                                    image: 'https://images.pexels.com/photos/2929906/pexels-photo-2929906.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Historic Site',
                                    rating: 4.9,
                                    price: 'S/152'
                                },
                                {
                                    name: 'Sacred Valley',
                                    description: 'Valley in the Andes of Peru close to Cusco and the ancient city of Machu Picchu.',
                                    image: 'https://images.pexels.com/photos/18525816/pexels-photo-18525816/free-photo-of-sacred-valley-near-cusco-peru.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Natural Wonder',
                                    rating: 4.8,
                                    price: 'S/70'
                                }
                            ]
                        }
                    }
                }
            }
        }
    };

    // Event Listeners
    continentSelect.addEventListener('change', handleContinentChange);
    countrySelect.addEventListener('change', handleCountryChange);
    citySelect.addEventListener('change', handleCityChange);

    // Handle continent selection
    function handleContinentChange() {
        const selectedContinent = continentSelect.value;

        // Reset country and city dropdowns
        countrySelect.innerHTML = '<option value="">Select a country</option>';
        citySelect.innerHTML = '<option value="">Select a city</option>';

        // Disable city dropdown
        citySelect.disabled = true;

        if (selectedContinent) {
            // Enable country dropdown
            countrySelect.disabled = false;

            // Populate countries dropdown
            const countries = locationData[selectedContinent].countries;
            for (const countryCode in countries) {
                const option = document.createElement('option');
                option.value = countryCode;
                option.textContent = countries[countryCode].name;
                countrySelect.appendChild(option);
            }

            // Display attractions for the selected continent
            displayAttractions('continent', selectedContinent);
        } else {
            // If no continent selected, disable country dropdown and hide results
            countrySelect.disabled = true;
            hideResults();
        }
    }

    // Handle country selection
    function handleCountryChange() {
        const selectedContinent = continentSelect.value;
        const selectedCountry = countrySelect.value;

        // Reset city dropdown
        citySelect.innerHTML = '<option value="">Select a city</option>';

        if (selectedCountry) {
            // Enable city dropdown
            citySelect.disabled = false;

            // Populate cities dropdown
            const cities = locationData[selectedContinent].countries[selectedCountry].cities;
            for (const cityCode in cities) {
                const option = document.createElement('option');
                option.value = cityCode;
                option.textContent = cities[cityCode].name;
                citySelect.appendChild(option);
            }

            // Display attractions for the selected country
            displayAttractions('country', selectedCountry, selectedContinent);
        } else {
            // If no country selected, disable city dropdown and show continent attractions
            citySelect.disabled = true;
            displayAttractions('continent', selectedContinent);
        }
    }

    // Handle city selection
    function handleCityChange() {
        const selectedContinent = continentSelect.value;
        const selectedCountry = countrySelect.value;
        const selectedCity = citySelect.value;

        if (selectedCity) {
            // Display attractions for the selected city
            displayAttractions('city', selectedCity, selectedContinent, selectedCountry);
        } else {
            // If no city selected, show country attractions
            displayAttractions('country', selectedCountry, selectedContinent);
        }
    }

    // Display attractions based on selection level (continent, country, or city)
    function displayAttractions(level, selectedValue, continentValue = null, countryValue = null) {
        let attractions = [];
        let locationName = '';

        // Get attractions and location name based on selection level
        if (level === 'continent') {
            // Gather all attractions from all countries in this continent
            attractions = [];
            const continentData = locationData[selectedValue];

            // Loop through all countries in the continent
            for (const countryCode in continentData.countries) {
                const countryData = continentData.countries[countryCode];

                // Loop through all cities in the country
                for (const cityCode in countryData.cities) {
                    // Add each attraction with country and city info
                    attractions = attractions.concat(countryData.cities[cityCode].attractions.map(attr => {
                        return {
                            ...attr,
                            country: countryCode,
                            city: cityCode
                        };
                    }));
                }
            }

            locationName = locationData[selectedValue].name;
            resultsTitle.innerHTML = `Top Attractions in <span id="city-name">${locationName}</span>`;
            resultsSubtitle.textContent = 'Discover amazing experiences across the continent';
        }
        else if (level === 'country') {
            attractions = [];
            const countryData = locationData[continentValue].countries[selectedValue];

            // Collect all attractions from this country
            for (const cityCode in countryData.cities) {
                attractions = attractions.concat(countryData.cities[cityCode].attractions.map(attr => {
                    return { ...attr, city: cityCode };
                }));
            }

            locationName = countryData.name;
            resultsTitle.innerHTML = `Top Attractions in <span id="city-name">${locationName}</span>`;
            resultsSubtitle.textContent = 'Discover amazing experiences across the country';
        }
        else if (level === 'city') {
            const cityData = locationData[continentValue].countries[countryValue].cities[selectedValue];
            attractions = cityData.attractions;
            locationName = cityData.name;
            resultsTitle.innerHTML = `Top Attractions in <span id="city-name">${locationName}</span>`;
            resultsSubtitle.textContent = 'Discover amazing experiences in this city';
        }

        // Clear previous results
        resultsGrid.innerHTML = '';

        // Show results section, hide initial state
        initialState.classList.add('hidden');
        resultsWrapper.classList.remove('hidden');

        // Check if there are attractions
        if (attractions && attractions.length > 0) {
            // Hide no results message if visible
            noResults.classList.add('hidden');
            resultsGrid.classList.remove('hidden');

            // Display attractions
            attractions.forEach(attraction => {
                const attractionCard = createAttractionCard(attraction);
                resultsGrid.appendChild(attractionCard);
            });
        } else {
            // Show no results message
            resultsGrid.classList.add('hidden');
            noResults.classList.remove('hidden');
        }
    }


    // Create attraction card
    function createAttractionCard(attraction) {
        const card = document.createElement('div');
        card.className = 'attraction-card';

        // Add location info to description when needed
        let description = attraction.description;
        if (attraction.city && citySelect.value === '') {
            if (countrySelect.value === '' && attraction.country) {
                // We're at continent level - show both country and city
                const countryName = getCountryName(attraction.country, continentSelect.value);
                const cityName = getCityName(attraction.city, continentSelect.value, attraction.country);
                if (cityName && countryName && !description.includes(cityName)) {
                    description += ` in ${cityName}, ${countryName}`;
                }
            } else {
                // We're at country level - just show city
                const cityName = getCityName(attraction.city, continentSelect.value, countrySelect.value);
                if (cityName && !description.includes(cityName)) {
                    description += ` in ${cityName}`;
                }
            }
        }

        card.innerHTML = `
        <div class="attraction-image">
            <img src="${attraction.image}" alt="${attraction.name}" onerror="this.src='images/placeholder.jpg'">
            <div class="attraction-category">${attraction.category}</div>
        </div>
        <div class="attraction-content">
            <h3>${attraction.name}</h3>
            <p>${description}</p>
            <div class="attraction-meta">
                <span><i class="fa-solid fa-star"></i> ${attraction.rating}</span>
                <span class="price">${attraction.price}</span>
            </div>
            <div class="attraction-actions">
                <a href="#" class="view-details">View Details</a>
            </div>
        </div>
    `;

        return card;
    }

    // Helper function to get city name from city code
    function getCityName(cityCode, continentCode, countryCode) {
        try {
            if (continentCode && countryCode) {
                return locationData[continentCode].countries[countryCode].cities[cityCode].name;
            } else if (cityCode.includes(',')) {
                // City name might be directly included in format "City, Country"
                return cityCode.split(',')[0];
            }
        } catch (e) {
            return null;
        }
        return null;
    }
    function getCountryName(countryCode, continentCode) {
        try {
            if (continentCode) {
                return locationData[continentCode].countries[countryCode].name;
            }
        } catch (e) {
            return null;
        }
        return null;
    }

    // Hide results and show initial state
    function hideResults() {
        initialState.classList.remove('hidden');
        resultsWrapper.classList.add('hidden');
    }

    // Sticky navigation
    const mainHeader = document.getElementById('mainHeader');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > 100) {
            mainHeader.classList.add('sticky');

            if (scrollTop > lastScrollTop) {
                // Scrolling down
                mainHeader.classList.add('hide');
            } else {
                // Scrolling up
                mainHeader.classList.remove('hide');
            }
        } else {
            mainHeader.classList.remove('sticky');
            mainHeader.classList.remove('hide');
        }

        lastScrollTop = scrollTop;
    });

    // Mobile menu toggle
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');

    hamburger.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        hamburger.classList.toggle('active');
    });

    // Add click event to "View Details" buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('view-details')) {
            e.preventDefault();
            alert('View details functionality would open a detailed page for this attraction.');
        }
    });
});