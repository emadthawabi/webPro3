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
                    image: 'https://i.natgeofe.com/k/c41b4f59-181c-4747-ad20-ef69987c8d59/eiffel-tower-night.jpg?wp=1&w=1084.125&h=1627.5',
                    category: 'Landmark',
                    rating: 4.7,
                    price: '€25',
                    country: 'france',
                    city: 'paris',
                    details: 'The Eiffel Tower is a wrought-iron lattice tower on the Champ de Mars in Paris, France. It is named after the engineer Gustave Eiffel, whose company designed and built the tower from 1887 to 1889. It was initially criticized by some of France\'s leading artists and intellectuals for its design, but it has become a global cultural icon of France and one of the most recognizable structures in the world. The Eiffel Tower is 330 meters (1,083 ft) tall and was the tallest man-made structure in the world for 41 years until the Chrysler Building in New York City was finished in 1930.'
                },
                {
                    name: 'Colosseum',
                    description: 'Ancient amphitheater in Rome, Italy',
                    image: 'https://images.pexels.com/photos/532263/pexels-photo-532263.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Historic Site',
                    rating: 4.8,
                    price: '€16',
                    country: 'italy',
                    city: 'rome',
                    details: 'The Colosseum is an oval amphitheatre in the centre of the city of Rome, Italy. It is the largest ancient amphitheatre ever built, and is still the largest standing amphitheatre in the world today, despite its age. Construction began under the emperor Vespasian (r. 69–79 AD) in 72 and was completed in 80 AD under his successor and heir, Titus. The Colosseum could hold an estimated 50,000 to 80,000 spectators and was used for gladiatorial contests and public spectacles.'
                },
                {
                    name: 'Louvre Museum',
                    description: 'World\'s largest art museum in Paris, France',
                    image: 'https://images.pexels.com/photos/2363/france-landmark-lights-night.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    category: 'Museum',
                    rating: 4.8,
                    price: '€17',
                    country: 'france',
                    city: 'paris',
                    details: 'The Louvre Museum is the world\'s largest art museum and a historic monument in Paris, France. A central landmark of the city, it is located on the Right Bank of the Seine. The museum is housed in the Louvre Palace, originally built as the Louvre castle in the late 12th to 13th century. The Louvre displays approximately 38,000 objects from prehistory to the 21st century. The Louvre is the world\'s most visited museum, receiving more than 10 million visitors in 2018.'
                },
                {
                    name: 'Trevi Fountain',
                    description: 'Baroque fountain in Rome, Italy',
                    image: 'https://www.museumsrome.com/images/Fontana-di-Trevi-a-Roma.jpg',
                    category: 'Landmark',
                    rating: 4.8,
                    price: 'Free',
                    country: 'italy',
                    city: 'rome',
                    details: 'The Trevi Fountain is a fountain in the Trevi district in Rome, Italy, designed by Italian architect Nicola Salvi and completed by Giuseppe Pannini and several others. Standing 26.3 metres high and 49.15 metres wide, it is the largest Baroque fountain in the city and one of the most famous fountains in the world. The fountain has appeared in several notable films, including Federico Fellini\'s "La Dolce Vita".'
                },
                {
                    name: 'Promenade des Anglais',
                    description: 'Famous promenade in Nice, France',
                    image: 'https://www.hotel-la-perouse.com/wp-content/uploads/sites/558/2024/09/DSC04390.jpg',
                    category: 'Landmark',
                    rating: 4.6,
                    price: 'Free',
                    country: 'france',
                    city: 'nice',
                    details: 'The Promenade des Anglais is a prominent road along the Mediterranean coast in Nice, France. It extends from the airport on the west to the Quai des États-Unis on the east, a distance of approximately 7 km. The promenade takes its name from the English visitors who paid for it in the first half of the 19th century. It has become one of the most famous seafront promenades in Europe.'
                },
                {
                    name: 'Grand Canal',
                    description: 'Main waterway in Venice, Italy',
                    image: 'https://cdn.britannica.com/63/153463-050-06B6270D/Grand-Canal-Venice.jpg',
                    category: 'Landmark',
                    rating: 4.9,
                    price: 'Free',
                    country: 'italy',
                    city: 'venice',
                    details: 'The Grand Canal is a channel in Venice, Italy. It forms one of the major water-traffic corridors in the city. Public transport is provided by water buses and private water taxis, and many tourists explore the canal by gondola. One end of the canal leads into the lagoon near the Santa Lucia railway station and the other end leads into the basin at San Marco; in between, it makes a large reverse-S shape through the central districts of Venice.'
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
                                    image: 'https://i.natgeofe.com/k/c41b4f59-181c-4747-ad20-ef69987c8d59/eiffel-tower-night.jpg?wp=1&w=1084.125&h=1627.5',
                                    category: 'Landmark',
                                    rating: 4.7,
                                    price: '€25',
                                    details: 'The Eiffel Tower is a wrought-iron lattice tower on the Champ de Mars in Paris, France. It is named after the engineer Gustave Eiffel, whose company designed and built the tower from 1887 to 1889. It was initially criticized by some of France\'s leading artists and intellectuals for its design, but it has become a global cultural icon of France and one of the most recognizable structures in the world. The Eiffel Tower is 330 meters (1,083 ft) tall and was the tallest man-made structure in the world for 41 years until the Chrysler Building in New York City was finished in 1930.'
                                },
                                {
                                    name: 'Louvre Museum',
                                    description: 'World\'s largest art museum and historic monument housing the Mona Lisa.',
                                    image: 'https://images.pexels.com/photos/2363/france-landmark-lights-night.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Museum',
                                    rating: 4.8,
                                    price: '€17',
                                    details: 'The Louvre Museum is the world\'s largest art museum and a historic monument in Paris, France. A central landmark of the city, it is located on the Right Bank of the Seine. The museum is housed in the Louvre Palace, originally built as the Louvre castle in the late 12th to 13th century. The Louvre displays approximately 38,000 objects from prehistory to the 21st century. The Louvre is the world\'s most visited museum, receiving more than 10 million visitors in 2018.'
                                },
                                {
                                    name: 'Notre-Dame Cathedral',
                                    description: 'Medieval Catholic cathedral on the Île de la Cité known for its French Gothic architecture.',
                                    image: 'https://images.pexels.com/photos/15760151/pexels-photo-15760151/free-photo-of-notre-dame-cathedral-in-paris-france.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Religious Site',
                                    rating: 4.7,
                                    price: 'Free',
                                    details: 'Notre-Dame de Paris, referred to simply as Notre-Dame, is a medieval Catholic cathedral on the Île de la Cité in the 4th arrondissement of Paris. The cathedral was consecrated to the Virgin Mary and considered to be one of the finest examples of French Gothic architecture. Following a major fire in April 2019 that destroyed the spire and most of the roof, the cathedral is currently undergoing a massive reconstruction project with plans to reopen in 2024.'
                                },
                                {
                                    name: 'Montmartre',
                                    description: 'Large hill in Paris\'s 18th arrondissement known for its artistic history and the Sacré-Cœur Basilica.',
                                    image: 'https://images.pexels.com/photos/705764/pexels-photo-705764.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'District',
                                    rating: 4.6,
                                    price: 'Free',
                                    details: 'Montmartre is a large hill in Paris\'s 18th arrondissement. It is 130 m (430 ft) high and gives its name to the surrounding district, part of the Right Bank in the northern section of the city. The historic district established by the City of Paris in 1995 is bordered by rue Caulaincourt and rue Custine on the north, rue de Clignancourt on the east, and boulevard de Clichy and boulevard de Rochechouart to the south, containing 60 ha. Montmartre is primarily known for its artistic history, the white-domed Basilica of the Sacré-Cœur on its summit, and as a nightclub district.'
                                }
                            ]
                        },
                        'nice': {
                            name: 'Nice',
                            attractions: [
                                {
                                    name: 'Promenade des Anglais',
                                    description: 'Famous promenade along the Mediterranean coastline.',
                                    image: 'https://www.hotel-la-perouse.com/wp-content/uploads/sites/558/2024/09/DSC04390.jpg',
                                    category: 'Landmark',
                                    rating: 4.6,
                                    price: 'Free',
                                    details: 'The Promenade des Anglais is a prominent road along the Mediterranean coast in Nice, France. It extends from the airport on the west to the Quai des États-Unis on the east, a distance of approximately 7 km. The promenade takes its name from the English visitors who paid for it in the first half of the 19th century. It has become one of the most famous seafront promenades in Europe.'
                                },
                                {
                                    name: 'Old Town (Vieux Nice)',
                                    description: 'Charming old district with narrow streets, colorful buildings, and local markets.',
                                    image: 'https://www.hotelfollower.com/wp-content/uploads/old-town-vieux-nice.jpg',
                                    category: 'District',
                                    rating: 4.7,
                                    price: 'Free',
                                    details: 'The Old Town of Nice (Vieux Nice) is a charming maze of narrow streets and colorful buildings filled with shops, restaurants, and small squares. The area has retained much of its historic character with its Italian-influenced architecture, as Nice was not part of France until 1860. The famous Cours Saleya market is located here, where locals and tourists alike can purchase fresh flowers, produce, and local specialties. The Old Town is best explored on foot, allowing visitors to discover its hidden treasures and experience the authentic atmosphere of this Mediterranean city.'
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
                                    price: '€16',
                                    details: 'The Colosseum is an oval amphitheatre in the centre of the city of Rome, Italy. It is the largest ancient amphitheatre ever built, and is still the largest standing amphitheatre in the world today, despite its age. Construction began under the emperor Vespasian (r. 69–79 AD) in 72 and was completed in 80 AD under his successor and heir, Titus. The Colosseum could hold an estimated 50,000 to 80,000 spectators and was used for gladiatorial contests and public spectacles.'
                                },

                                {
                                    name: 'Trevi Fountain',
                                    description: 'Baroque fountain designed by Italian architect Nicola Salvi.',
                                    image: 'https://www.museumsrome.com/images/Fontana-di-Trevi-a-Roma.jpg',
                                    category: 'Landmark',
                                    rating: 4.8,
                                    price: 'Free',
                                    details: 'The Trevi Fountain is a fountain in the Trevi district in Rome, Italy, designed by Italian architect Nicola Salvi and completed by Giuseppe Pannini and several others. Standing 26.3 metres high and 49.15 metres wide, it is the largest Baroque fountain in the city and one of the most famous fountains in the world. The fountain has appeared in several notable films, including Federico Fellini\'s "La Dolce Vita".'
                                }
                            ]
                        },
                        'venice': {
                            name: 'Venice',
                            attractions: [
                                {
                                    name: 'Grand Canal',
                                    description: 'Main waterway through the central districts of Venice.',
                                    image: 'https://cdn.britannica.com/63/153463-050-06B6270D/Grand-Canal-Venice.jpg',
                                    category: 'Landmark',
                                    rating: 4.9,
                                    price: 'Free',
                                    details: 'The Grand Canal is a channel in Venice, Italy. It forms one of the major water-traffic corridors in the city. Public transport is provided by water buses and private water taxis, and many tourists explore the canal by gondola. One end of the canal leads into the lagoon near the Santa Lucia railway station and the other end leads into the basin at San Marco; in between, it makes a large reverse-S shape through the central districts of Venice.'
                                },
                                {
                                    name: 'St. Mark\'s Basilica',
                                    description: 'Cathedral church of the Roman Catholic Archdiocese of Venice.',
                                    image: 'https://images.pexels.com/photos/3566187/pexels-photo-3566187.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                                    category: 'Religious Site',
                                    rating: 4.8,
                                    price: '€3',
                                    details: 'St Mark\'s Basilica is the cathedral church of the Roman Catholic Archdiocese of Venice. It is the most famous of the city\'s churches and one of the best-known examples of Italo-Byzantine architecture. It lies at the eastern end of the Piazza San Marco, adjacent and connected to the Doge\'s Palace. Originally it was the chapel of the Doge, and has been the city\'s cathedral only since 1807. The basilica is known for its opulent design, gold mosaics, and its status as a symbol of Venetian wealth and power.'
                                }
                            ]
                        }
                    }
                }
            }
        },
        // Rest of the continents remain the same
        // Add these continents to the locationData object
        'north_america': {
            name: 'North America',
            attractions: [], // Will be populated from countries
            countries: {
                'usa': {
                    name: 'United States',
                    cities: {
                        'new_york': {
                            name: 'New York',
                            attractions: [
                                {
                                    name: 'Statue of Liberty',
                                    description: 'Famous neoclassical sculpture on Liberty Island, a symbol of freedom.',
                                    image: 'https://images.pexels.com/photos/290386/pexels-photo-290386.jpeg',
                                    category: 'Landmark',
                                    rating: 4.7,
                                    price: '$24',
                                    details: 'The Statue of Liberty is a colossal neoclassical sculpture on Liberty Island in New York Harbor. The copper statue was a gift from the people of France to the people of the United States. The statue was dedicated on October 28, 1886, and has since become an iconic symbol of freedom and the United States.'
                                },
                                {
                                    name: 'Central Park',
                                    description: 'Urban park in Manhattan covering 843 acres between the Upper West and East Sides.',
                                    image: 'https://images.pexels.com/photos/1070945/pexels-photo-1070945.jpeg',
                                    category: 'Park',
                                    rating: 4.8,
                                    price: 'Free',
                                    details: 'Central Park is an urban park in New York City located between the Upper West and Upper East Sides of Manhattan. It is the most visited urban park in the United States with an estimated 42 million visitors annually, and is the most filmed location in the world.'
                                }
                            ]
                        },
                        'los_angeles': {
                            name: 'Los Angeles',
                            attractions: [
                                {
                                    name: 'Hollywood Sign',
                                    description: 'Iconic landmark in the Hollywood Hills area of Los Angeles.',
                                    image: 'https://images.pexels.com/photos/2404843/pexels-photo-2404843.jpeg',
                                    category: 'Landmark',
                                    rating: 4.6,
                                    price: 'Free',
                                    details: 'The Hollywood Sign is an American landmark and cultural icon overlooking Hollywood, Los Angeles, California. Originally erected in 1923, the sign was built as an advertisement for a local real estate development, but has since become a world-famous symbol of Hollywood and the entertainment industry.'
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
                                    description: 'Concrete communications and observation tower in downtown Toronto.',
                                    image: 'https://images.pexels.com/photos/2478248/pexels-photo-2478248.jpeg',
                                    category: 'Landmark',
                                    rating: 4.6,
                                    price: 'CA$40',
                                    details: 'The CN Tower is a 553.3 m-high concrete communications and observation tower in downtown Toronto, Ontario, Canada. Built on the former Railway Lands, it was completed in 1976. Its name "CN" originally referred to Canadian National, the railway company that built the tower.'
                                }
                            ]
                        },
                        'vancouver': {
                            name: 'Vancouver',
                            attractions: [
                                {
                                    name: 'Stanley Park',
                                    description: 'Urban park that borders the downtown of Vancouver, featuring dense forest and scenic views.',
                                    image: 'https://images.pexels.com/photos/2611070/pexels-photo-2611070.jpeg',
                                    category: 'Park',
                                    rating: 4.8,
                                    price: 'Free',
                                    details: 'Stanley Park is a 405-hectare public park that borders the downtown of Vancouver and is almost entirely surrounded by waters of Vancouver Harbour and English Bay. The park has a long history and was one of the first areas to be explored in the city.'
                                }
                            ]
                        }
                    }
                },
                'mexico': {
                    name: 'Mexico',
                    cities: {
                        'mexico_city': {
                            name: 'Mexico City',
                            attractions: [
                                {
                                    name: 'Palacio de Bellas Artes',
                                    description: 'Cultural center and prominent cultural venue in Mexico City.',
                                    image: 'https://images.pexels.com/photos/5117913/pexels-photo-5117913.jpeg',
                                    category: 'Cultural Site',
                                    rating: 4.8,
                                    price: '80 MXN',
                                    details: 'The Palacio de Bellas Artes (Palace of Fine Arts) is a prominent cultural center in Mexico City. It has hosted important exhibitions of painting, sculpture, and photography, as well as notable events in music, dance, theater, opera, and literature.'
                                }
                            ]
                        }
                    }
                }
            }
        },
        'asia': {
            name: 'Asia',
            attractions: [], // Will be populated from countries
            countries: {
                'japan': {
                    name: 'Japan',
                    cities: {
                        'tokyo': {
                            name: 'Tokyo',
                            attractions: [
                                {
                                    name: 'Tokyo Skytree',
                                    description: 'Broadcasting and observation tower in Sumida, Tokyo.',
                                    image: 'https://images.pexels.com/photos/2506923/pexels-photo-2506923.jpeg',
                                    category: 'Landmark',
                                    rating: 4.5,
                                    price: '¥2,100',
                                    details: 'Tokyo Skytree is a broadcasting and observation tower in Sumida, Tokyo. It became the tallest structure in Japan in 2010 and reached its full height of 634 meters in March 2011, making it the tallest tower in the world and the second tallest structure in the world after the Burj Khalifa.'
                                },
                                {
                                    name: 'Meiji Shrine',
                                    description: 'Shinto shrine dedicated to Emperor Meiji and Empress Shōken.',
                                    image: 'https://travel.gaijinpot.com/app/uploads/sites/6/2019/05/Meiji-Jingu-2.jpg',
                                    category: 'Religious Site',
                                    rating: 4.7,
                                    price: 'Free',
                                    details: 'Meiji Shrine is a Shinto shrine dedicated to the deified spirits of Emperor Meiji and his wife, Empress Shōken. It is located in Shibuya, Tokyo. The shrine was built in 1920 to commemorate Emperor Meiji, who died in 1912. The lush forest surrounding the shrine consists of 120,000 trees of 365 different species.'
                                }
                            ]
                        },
                        'kyoto': {
                            name: 'Kyoto',
                            attractions: [
                                {
                                    name: 'Fushimi Inari Shrine',
                                    description: 'Shinto shrine famous for its thousands of vermilion torii gates.',
                                    image: 'https://images.pexels.com/photos/1440476/pexels-photo-1440476.jpeg',
                                    category: 'Religious Site',
                                    rating: 4.9,
                                    price: 'Free',
                                    details: 'Fushimi Inari Shrine is an important Shinto shrine in southern Kyoto. It is famous for its thousands of vermilion torii gates, which straddle a network of trails behind its main buildings. The trails lead into the wooded forest of the sacred Mount Inari, which stands at 233 meters and belongs to the shrine grounds.'
                                }
                            ]
                        }
                    }
                },
                'china': {
                    name: 'China',
                    cities: {
                        'beijing': {
                            name: 'Beijing',
                            attractions: [
                                {
                                    name: 'Great Wall of China',
                                    description: 'Ancient wall built across the historical northern borders of China.',
                                    image: 'https://images.pexels.com/photos/1654748/pexels-photo-1654748.jpeg',
                                    category: 'Historic Site',
                                    rating: 4.9,
                                    price: '¥40',
                                    details: 'The Great Wall of China is a series of fortifications that were built across the historical northern borders of ancient Chinese states and Imperial China as protection against various nomadic groups. Several walls were built from as early as the 7th century BC, with selective stretches later joined together by Qin Shi Huang (220–206 BC), the first emperor of China.'
                                }
                            ]
                        },
                        'shanghai': {
                            name: 'Shanghai',
                            attractions: [
                                {
                                    name: 'The Bund',
                                    description: 'Waterfront area in central Shanghai with iconic skyline views.',
                                    image: 'https://images.pexels.com/photos/1366957/pexels-photo-1366957.jpeg',
                                    category: 'District',
                                    rating: 4.8,
                                    price: 'Free',
                                    details: 'The Bund is a waterfront area in central Shanghai, featuring buildings and wharves that line the western bank of the Huangpu River. The area has dozens of historical buildings that once housed banks and trading houses from the United Kingdom, France, the United States, and other countries.'
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
                                    description: 'Complex of buildings serving as the official residence of the Kings of Thailand.',
                                    image: 'https://images.pexels.com/photos/1134176/pexels-photo-1134176.jpeg',
                                    category: 'Historic Site',
                                    rating: 4.7,
                                    price: '500 THB',
                                    details: 'The Grand Palace is a complex of buildings at the heart of Bangkok, Thailand. It has been the official residence of the Kings of Thailand since 1782. The Grand Palace is divided into four main courts, separated by numerous walls and gates: the Outer Court, the Middle Court, the Inner Court and the Temple of the Emerald Buddha.'
                                }
                            ]
                        }
                    }
                }
            }
        },
        'south_america': {
            name: 'South America',
            attractions: [], // Will be populated from countries
            countries: {
                'brazil': {
                    name: 'Brazil',
                    cities: {
                        'rio_de_janeiro': {
                            name: 'Rio de Janeiro',
                            attractions: [
                                {
                                    name: 'Christ the Redeemer',
                                    description: 'Art Deco statue of Jesus Christ at the summit of Mount Corcovado.',
                                    image: 'https://images.pexels.com/photos/2818895/pexels-photo-2818895.jpeg',
                                    category: 'Landmark',
                                    rating: 4.8,
                                    price: 'R$87',
                                    details: 'Christ the Redeemer is an Art Deco statue of Jesus Christ in Rio de Janeiro, Brazil, created by French sculptor Paul Landowski and built by the Brazilian engineer Heitor da Silva Costa, in collaboration with the French engineer Albert Caquot. The statue is 30 meters tall, excluding its 8-meter pedestal, and its arms stretch 28 meters wide.'
                                },
                                {
                                    name: 'Copacabana Beach',
                                    description: 'Famous beach in the copacabana district of Rio de Janeiro.',
                                    image: 'https://images.pexels.com/photos/2868242/pexels-photo-2868242.jpeg',
                                    category: 'Beach',
                                    rating: 4.7,
                                    price: 'Free',
                                    details: 'Copacabana Beach is one of the most famous beaches in the world. It is located in the Copacabana neighborhood of Rio de Janeiro, Brazil. The beach is 4 km long and is known for its iconic black and white mosaic promenade designed by Brazilian landscape architect Roberto Burle Marx.'
                                }
                            ]
                        },

                    }
                },
                'peru': {
                    name: 'Peru',
                    cities: {
                        'cusco': {
                            name: 'Cusco',
                            attractions: [
                                {
                                    name: 'Machu Picchu',
                                    description: '15th-century Inca citadel located on a mountain ridge above the Sacred Valley.',
                                    image: 'https://www.machupicchu.org/wp-content/uploads/top-destinations-machu-picchu.jpg',
                                    category: 'Historic Site',
                                    rating: 4.9,
                                    price: '152 PEN',
                                    details: 'Machu Picchu is a 15th-century Inca citadel situated on a mountain ridge 2,430 meters above sea level. It is located in the Cusco Region, Urubamba Province, Machupicchu District in Peru, above the Sacred Valley, which is 80 kilometers northwest of Cusco. Most archaeologists believe that Machu Picchu was constructed as an estate for the Inca emperor Pachacuti.'
                                }
                            ]
                        },
                        'lima': {
                            name: 'Lima',
                            attractions: [
                                {
                                    name: 'Plaza Mayor',
                                    description: 'Historic center of Lima and the birthplace of the city.',
                                    image: 'https://images.pexels.com/photos/13109544/pexels-photo-13109544.jpeg',
                                    category: 'Historic Site',
                                    rating: 4.5,
                                    price: 'Free',
                                    details: 'Plaza Mayor, also known as Plaza de Armas, is the birthplace of the city of Lima, the capital of Peru. It is surrounded by palaces and important buildings such as the Government Palace, the Cathedral of Lima, the Archbishop\'s Palace of Lima, the Municipal Palace, and the Palace of the Union.'
                                }
                            ]
                        }
                    }
                },
                'argentina': {
                    name: 'Argentina',
                    cities: {
                        'buenos_aires': {
                            name: 'Buenos Aires',
                            attractions: [
                                {
                                    name: 'Recoleta Cemetery',
                                    description: 'Famous cemetery with ornate mausoleums and the grave of Eva Perón.',
                                    image: 'https://stephentravels.com/wp-content/uploads/2021/05/argentina_buenos-aires_recoleta-cemetery_passages.jpg',
                                    category: 'Historic Site',
                                    rating: 4.7,
                                    price: 'Free',
                                    details: 'Recoleta Cemetery is a cemetery located in the Recoleta neighborhood of Buenos Aires, Argentina. It contains the graves of notable people, including Eva Perón, presidents of Argentina, Nobel Prize winners, and other important figures. The cemetery is known for its many elaborate marble mausoleums.'
                                }
                            ]
                        }
                    }
                }
            }
        },


        'africa': {
            name: 'Africa',
            attractions: [], // Will be populated from countries
            countries: {
                'egypt': {
                    name: 'Egypt',
                    cities: {
                        'cairo': {
                            name: 'Cairo',
                            attractions: [
                                {
                                    name: 'Great Pyramids of Giza',
                                    description: 'Ancient Egyptian pyramids and the Great Sphinx on the Giza Plateau.',
                                    image: 'https://images.pexels.com/photos/71241/pexels-photo-71241.jpeg',
                                    category: 'Historic Site',
                                    rating: 4.8,
                                    price: '240 EGP',
                                    details: 'The Great Pyramids of Giza are the last remaining wonder of the ancient world. Built over 4,500 years ago, the complex includes the Great Pyramid of Khufu, the Pyramid of Khafre, the Pyramid of Menkaure, and the Great Sphinx. These massive structures were built as tombs for pharaohs and their consorts during Egypt\'s Old and Middle Kingdom periods.'
                                },

                            ]
                        },
                        'luxor': {
                            name: 'Luxor',
                            attractions: [

                                {
                                    name: 'Karnak Temple',
                                    description: 'Massive temple complex dedicated to the gods Amun, Mut, and Khonsu.',
                                    image: 'https://images.pexels.com/photos/11356076/pexels-photo-11356076.jpeg',
                                    category: 'Historic Site',
                                    rating: 4.8,
                                    price: '180 EGP',
                                    details: 'The Karnak Temple Complex, commonly known as Karnak, comprises a vast mix of decayed temples, chapels, pylons, and other buildings. The area was a sacred site for ancient Egyptians for over 2,000 years. The complex is a testament to the skill and scale of ancient Egyptian construction and religious practices.'
                                }
                            ]
                        }
                    }
                },
                'south_africa': {
                    name: 'South Africa',
                    cities: {
                        'cape_town': {
                            name: 'Cape Town',
                            attractions: [

                                {
                                    name: 'Robben Island',
                                    description: 'Island prison where Nelson Mandela was incarcerated for 18 years.',
                                    image: 'https://cdn.britannica.com/01/195301-050-05749BD2/Robben-Island-Table-Bay-prison-home-tourism-1999.jpg',
                                    category: 'Historic Site',
                                    rating: 4.6,
                                    price: 'R550',
                                    details: 'Robben Island is an island in Table Bay, 6.9 kilometers west of the coast of Cape Town. The island was used as a prison, leper colony, and mental hospital from the 17th to the 20th centuries. It is famous for being where Nelson Mandela was imprisoned for 18 of the 27 years he served behind bars before becoming South Africa\'s first black president.'
                                }
                            ]
                        },
                        'johannesburg': {
                            name: 'Johannesburg',
                            attractions: [
                                {
                                    name: 'Apartheid Museum',
                                    description: 'Museum illustrating the rise and fall of apartheid in South Africa.',
                                    image: 'https://www.andbeyond.com/wp-content/uploads/sites/5/Apartheid-Museum.jpg',
                                    category: 'Museum',
                                    rating: 4.7,
                                    price: 'R150',
                                    details: 'The Apartheid Museum is a museum in Johannesburg documenting the rise and fall of apartheid in South Africa. The museum illustrates the rise and fall of apartheid through film footage, photographs, text panels, and artifacts. The exhibits are designed to portray the racial segregation, political struggles, and the triumph of democracy over oppression.'
                                },
                                {
                                    name: 'Constitution Hill',
                                    description: 'Former prison complex that now houses South Africa\'s Constitutional Court.',
                                    image: 'https://images.pexels.com/photos/6782360/pexels-photo-6782360.jpeg',
                                    category: 'Historic Site',
                                    rating: 4.5,
                                    price: 'R120',
                                    details: 'Constitution Hill is a living museum that tells the story of South Africa\'s journey to democracy. The site is a former prison and military fort that bears testament to South Africa\'s turbulent past and, today, is home to the country\'s Constitutional Court, which endorses the rights of all citizens. Nelson Mandela, Mahatma Gandhi, and other political activists were detained here.'
                                }
                            ]
                        }
                    }
                },
                'morocco': {
                    name: 'Morocco',
                    cities: {
                        'marrakech': {
                            name: 'Marrakech',
                            attractions: [
                                {
                                    name: 'Jemaa el-Fnaa',
                                    description: 'Famous square and market place in the medina quarter (old city).',
                                    image: 'https://images.pexels.com/photos/4388164/pexels-photo-4388164.jpeg',
                                    category: 'Market',
                                    rating: 4.7,
                                    price: 'Free',
                                    details: 'Jemaa el-Fnaa is a square and market place in Marrakech\'s medina quarter. It remains the main square of Marrakech, used by locals and tourists alike. During the day it is predominantly occupied by orange juice stalls, water sellers, snake charmers, and henna artists. As the day progresses, the entertainment shifts to storytellers, magicians, and musicians, followed by food stalls that convert the square into a huge open-air restaurant.'
                                }

                            ]
                        },
                        'fez': {
                            name: 'Fez',
                            attractions: [
                                {
                                    name: 'Fes el Bali',
                                    description: 'UNESCO World Heritage Site and oldest walled part of Fez, featuring the famous tanneries.',
                                    image: 'https://upload.wikimedia.org/wikipedia/commons/5/5d/Fes_Bab_Bou_Jeloud_2011.jpg',
                                    category: 'Historic District',
                                    rating: 4.8,
                                    price: 'Free',
                                    details: 'Fes el Bali is the oldest walled part of Fez, Morocco. It was founded as the capital of the Idrisid dynasty between 789 and 808 AD. It is a UNESCO World Heritage site and is believed to be one of the world\'s largest urban pedestrian zones. The medina contains the University of Al Quaraouiyine, founded in 859, which is considered by some to be the oldest continuously functioning university in the world.'
                                }
                            ]
                        }
                    }
                },
                'kenya': {
                    name: 'Kenya',
                    cities: {
                        'nairobi': {
                            name: 'Nairobi',
                            attractions: [
                                {
                                    name: 'Nairobi National Park',
                                    description: 'Wildlife park located just 7 km from the city center of Nairobi.',
                                    image: 'https://www.campoeasafaris.com/wp-content/uploads/2021/05/nairobi-national-park-main-gate.jpeg',
                                    category: 'National Park',
                                    rating: 4.6,
                                    price: '$43',
                                    details: 'Nairobi National Park is a national park in Kenya that was established in 1946. It is located approximately 7 kilometers south of the center of Nairobi, Kenya\'s capital city, and is the only protected area in the world that neighbors a capital city. Despite its proximity to civilization and its relatively small size, the park is home to a large and diverse wildlife population.'
                                },

                            ]
                        },
                        'mombasa': {
                            name: 'Mombasa',
                            attractions: [

                                {
                                    name: 'Diani Beach',
                                    description: 'White sand beach with turquoise waters on the Indian Ocean coast.',
                                    image: 'https://www.tsavonationalparkkenya.com/wp-content/uploads/2023/04/istockphoto-1226321192-612x612-1.jpg',
                                    category: 'Beach',
                                    rating: 4.9,
                                    price: 'Free',
                                    details: 'Diani Beach is a major beach resort on the Indian Ocean coast of Kenya. It has been voted Africa\'s leading beach destination for several years running. The beach is about 17 kilometers long, and is known for its coral reefs, black-and-white colobus monkeys, and for the closely located Shimba Hills National Reserve.'
                                }
                            ]
                        }
                    }
                },
                'tanzania': {
                    name: 'Tanzania',
                    cities: {
                        'dar_es_salaam': {
                            name: 'Dar es Salaam',
                            attractions: [
                                {
                                    name: 'National Museum of Tanzania',
                                    description: 'Consortium of museums displaying Tanzania\'s history and cultural heritage.',
                                    image: 'https://images.pexels.com/photos/210204/pexels-photo-210204.jpeg',
                                    category: 'Museum',
                                    rating: 4.3,
                                    price: '$10',
                                    details: 'The National Museum of Tanzania is a consortium of five Tanzanian museums whose purpose is to preserve and display exhibits about the history and natural environment of Tanzania. The museum displays important fossils of some of the earliest human ancestors discovered in Tanzania at Olduvai Gorge by Louis Leakey, as well as traditional artifacts from various regions of Tanzania.'
                                }
                            ]
                        },
                        'zanzibar_city': {
                            name: 'Zanzibar City',
                            attractions: [
                                {
                                    name: 'Stone Town',
                                    description: 'UNESCO World Heritage Site known for its narrow alleys, historic buildings, and cultural significance.',
                                    image: 'https://www.africa.com/wp-content/uploads/2025/01/Stone-Town-Zanzibar-Iconic-Site-930x500.jpg',
                                    category: 'Historic District',
                                    rating: 4.7,
                                    price: 'Free',
                                    details: 'Stone Town is the old part of Zanzibar City and is a UNESCO World Heritage Site. It is a maze of narrow streets lined with buildings that reflect the diverse influences underlying Swahili culture. The buildings feature elements of Arab, Persian, Indian, European, and Swahili styles. Stone Town is particularly known for its ornate doors with brass studs, which were traditionally a sign of status and wealth.'
                                }
                            ]
                        }
                    }
                },
                'nigeria': {
                    name: 'Nigeria',
                    cities: {
                        'lagos': {
                            name: 'Lagos',
                            attractions: [
                                {
                                    name: 'Nike Art Gallery',
                                    description: 'Five-story gallery showcasing contemporary Nigerian art.',
                                    image: 'https://images.pexels.com/photos/139764/pexels-photo-139764.jpeg',
                                    category: 'Art Gallery',
                                    rating: 4.6,
                                    price: 'Free',
                                    details: 'The Nike Art Gallery is a five-story art gallery in Lagos, Nigeria. It is owned by Nike Davies-Okundaye, an internationally acclaimed Nigerian artist. The gallery features over 8,000 diverse artworks from various Nigerian artists, including paintings, sculptures, and traditional crafts. It is one of the largest art galleries in West Africa.'
                                }

                            ]
                        },
                        'abuja': {
                            name: 'Abuja',
                            attractions: [
                                {
                                    name: 'Aso Rock',
                                    description: 'Large monolith that serves as the backdrop for Nigeria\'s Presidential Complex.',
                                    image: 'https://images.pexels.com/photos/11042589/pexels-photo-11042589.jpeg',
                                    category: 'Natural Landmark',
                                    rating: 4.4,
                                    price: 'Free',
                                    details: 'Aso Rock is a large monolith in the heart of Abuja, Nigeria\'s capital city. It serves as the backdrop for Nigeria\'s Presidential Complex, which houses the residences and offices of the President of Nigeria and the Vice President. The rock is approximately 400 meters tall and has become one of the most recognizable symbols of Nigeria\'s political authority.'
                                }
                            ]
                        }
                    }
                }
            }
        }
    };

    // Add the same details to all other attractions (simulate)
    for (const continentKey in locationData) {
        const continent = locationData[continentKey];

        // Process countries
        for (const countryKey in continent.countries) {
            const country = continent.countries[countryKey];

            // Process cities
            for (const cityKey in country.cities) {
                const city = country.cities[cityKey];

                // Add details to attractions if not present
                city.attractions.forEach(attraction => {
                    if (!attraction.details) {
                        attraction.details = `Detailed information about ${attraction.name} would appear here, including opening hours, ticket information, historical facts, and visitor tips. This is a famous ${attraction.category.toLowerCase()} located in ${city.name}, ${country.name}.`;
                    }
                });
            }
        }
    }

    // Create modal elements for the attraction details popup
    const modal = document.createElement('div');
    modal.className = 'attraction-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-body">
                <div class="modal-image-container">
                    <img src="" alt="" id="modal-image">
                </div>
                <div class="modal-info">
                    <h2 id="modal-title"></h2>
                    <div class="modal-meta">
                        <span id="modal-category"></span>
                        <span id="modal-rating"><i class="fa-solid fa-star"></i> <span id="rating-value"></span></span>
                        <span id="modal-price"></span>
                    </div>
                    <div class="modal-description">
                        <p id="modal-description-text"></p>
                    </div>
                    <div class="modal-details">
                        <h3>Details</h3>
                        <p id="modal-details-text"></p>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Add CSS for the modal
    const modalStyle = document.createElement('style');
    modalStyle.textContent = `
        .attraction-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            width: 80%;
            max-width: 900px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            position: relative;
            animation: slideIn 0.4s;
        }
        
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .close-modal {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            color: #555;
            cursor: pointer;
            z-index: 10;
        }
        
        .close-modal:hover {
            color: #000;
        }
        
        .modal-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        
        .modal-image-container {
            width: 100%;
            height: 300px;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .modal-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .modal-info h2 {
            font-size: 26px;
            margin-bottom: 12px;
            color: #333;
        }
        
        .modal-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 15px;
            color: #555;
        }
        
        .modal-meta span i {
            color: #ffc107;
        }
        
        .modal-description {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .modal-details h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .modal-details p {
            line-height: 1.6;
            color: #555;
        }
        
        @media (min-width: 768px) {
            .modal-body {
                flex-direction: row;
                gap: 30px;
            }
            
            .modal-image-container {
                width: 40%;
                height: auto;
                max-height: 500px;
                margin-bottom: 0;
            }
            
            .modal-info {
                width: 60%;
            }
        }
    `;
    document.head.appendChild(modalStyle);

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
        // Create the card HTML structure
        card.innerHTML = `
            <div class="card-image">
                <img src="${attraction.image}" alt="${attraction.name}">
                <div class="card-category">${attraction.category}</div>
            </div>
            <div class="card-content">
                <h3>${attraction.name}</h3>
                <p>${description}</p>
                <div class="card-meta">
                    <span class="rating"><i class="fa-solid fa-star"></i> ${attraction.rating}</span>
                    <span class="price">${attraction.price}</span>
                </div>
                <button class="details-btn" data-attraction='${JSON.stringify(attraction).replace(/'/g, "&#39;")}'>View Details</button>
            </div>
        `;

        // Add event listener to the details button
        card.querySelector('.details-btn').addEventListener('click', function() {
            showAttractionDetails(attraction);
        });

        return card;
    }

    // Show attraction details in modal
    function showAttractionDetails(attraction) {
        // Populate modal with attraction details
        document.getElementById('modal-image').src = attraction.image;
        document.getElementById('modal-image').alt = attraction.name;
        document.getElementById('modal-title').textContent = attraction.name;
        document.getElementById('modal-category').textContent = attraction.category;
        document.getElementById('rating-value').textContent = attraction.rating;
        document.getElementById('modal-price').textContent = attraction.price;
        document.getElementById('modal-description-text').textContent = attraction.description;
        document.getElementById('modal-details-text').textContent = attraction.details;

        // Show the modal
        const modal = document.querySelector('.attraction-modal');
        modal.style.display = 'block';

        // Prevent body scrolling when modal is open
        document.body.style.overflow = 'hidden';

        // Close modal when clicking the close button
        document.querySelector('.close-modal').addEventListener('click', closeModal);

        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Helper function to hide results and show initial state
    function hideResults() {
        initialState.classList.remove('hidden');
        resultsWrapper.classList.add('hidden');
    }

    // Add CSS for attraction cards
    const cardStyle = document.createElement('style');
    cardStyle.textContent = `
        .attraction-card {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .attraction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .card-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .attraction-card:hover .card-image img {
            transform: scale(1.05);
        }
        
        .card-category {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .card-content {
            padding: 20px;
        }
        
        .card-content h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 18px;
            color: #333;
        }
        
        .card-content p {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
            line-height: 1.4;
            height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        
        .card-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
            color: #555;
        }
        
        .rating i {
            color: #ffc107;
            margin-right: 3px;
        }
        
        .details-btn {
            width: 100%;
            padding: 10px;
            background-color: #3dbb91;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        
        .details-btn:hover {
            background-color: #3dbb91;
        }
    `;
    document.head.appendChild(cardStyle);

    // Initialize with all dropdowns disabled except continent
    countrySelect.disabled = true;
    citySelect.disabled = true;

    // Add default AOS animations to attraction cards
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('aos-animate');
            }
        });
    });

    // Initialize filters with continent selection guide
    const filtersGuide = document.createElement('div');
    filtersGuide.className = 'filters-guide';
    filtersGuide.innerHTML = '<p><i class="fa-solid fa-info-circle"></i> Start by selecting a continent to explore attractions</p>';
    document.querySelector('.filters-container').appendChild(filtersGuide);

    // Add responsive features for mobile
    const mediaQuery = window.matchMedia('(max-width: 768px)');
    function handleScreenChange(e) {
        if (e.matches) {
            // Adjust layout for mobile
            document.querySelector('.filters-container').classList.add('mobile-filters');

            if (document.querySelector('.mobile-toggle') === null) {
                // Add filter toggle button for mobile
                const filterToggle = document.createElement('button');
                filterToggle.className = 'mobile-toggle';
                filterToggle.innerHTML = '<i class="fa-solid fa-filter"></i> Filters';
                document.querySelector('.search-section').prepend(filterToggle);

                filterToggle.addEventListener('click', function() {
                    document.querySelector('.filters-container').classList.toggle('show-filters');
                });
            }
        } else {
            // Adjust layout for desktop
            document.querySelector('.filters-container').classList.remove('mobile-filters', 'show-filters');
            const toggle = document.querySelector('.mobile-toggle');
            if (toggle) toggle.remove();
        }
    }

    // Initial call and listener for screen size changes
    handleScreenChange(mediaQuery);
    mediaQuery.addListener(handleScreenChange);

    // Add error handling for image loading
    function handleImageError(img) {
        img.onerror = function() {
            this.src = 'https://via.placeholder.com/400x300?text=Image+Not+Available';
        };
    }

    // Apply error handling to all images loaded in the results
    function applyImageErrorHandling() {
        const images = document.querySelectorAll('.card-image img, #modal-image');
        images.forEach(img => handleImageError(img));
    }

    // Add observer to trigger animations
    function setupAnimations() {
        const cards = document.querySelectorAll('.attraction-card');
        cards.forEach(card => {
            card.classList.add('aos-init');
            card.setAttribute('data-aos', 'fade-up');
            observer.observe(card);
        });
    }

    // Enhance the displayAttractions function to include animation setup and image error handling
    const originalDisplayAttractions = displayAttractions;
    displayAttractions = function(level, selectedValue, continentValue = null, countryValue = null) {
        originalDisplayAttractions(level, selectedValue, continentValue, countryValue);
        setupAnimations();
        applyImageErrorHandling();
    };

    // Add "Back to Top" button
    const backToTopBtn = document.createElement('button');
    backToTopBtn.id = 'back-to-top';
    backToTopBtn.innerHTML = '<i class="fa-solid fa-arrow-up"></i>';
    document.body.appendChild(backToTopBtn);

    // Style for "Back to Top" button
    const backToTopStyle = document.createElement('style');
    backToTopStyle.textContent = `
        #back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 20px;
            cursor: pointer;
            display: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s;
            z-index: 99;
        }
        
        #back-to-top:hover {
            background-color: #2980b9;
            transform: scale(1.1);
        }
    `;
    document.head.appendChild(backToTopStyle);

    // Show/hide "Back to Top" button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });

    // Scroll to top when button is clicked
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });


});